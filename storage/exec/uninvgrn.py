import pymysql
import configparser
from datetime import datetime
import math

class UninvGrnJob:
    def __init__(self, request):
        # --- connect DB ---
        self.conn = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database=request.get("database", "sysdb"),
            cursorclass=pymysql.cursors.DictCursor,
            autocommit=False
        )
        self.cursor = self.conn.cursor()

        self.page = "uninvgrn"
        self.fromdate = request["datefrom"]
        self.todate = request["dateto"]
        self.username = request["username"]
        self.compcode = request["compcode"]

        now_str = datetime.now().strftime('%Y-%m-%d %I:%M %p')
        self.filename = f"uninvgrn_Export {now_str}.xlsx"

    # ---------------------------
    # Job Queue Helpers
    # ---------------------------
    def start_job_queue(self):
        sql = """
            INSERT INTO sysdb.job_queue
            (compcode, page, filename, adduser, adddate, status, date, date_to)
            VALUES (%s,%s,%s,%s,NOW(),'PENDING',%s,%s)
        """
        self.cursor.execute(sql, (
            self.compcode, self.page, self.filename,
            self.username, self.fromdate, self.todate
        ))
        self.conn.commit()
        return self.cursor.lastrowid

    def stop_job_queue(self, idno_job_queue):
        sql = """
            UPDATE sysdb.job_queue
            SET finishdate = NOW(), status = 'DONE'
            WHERE idno = %s
        """
        self.cursor.execute(sql, (idno_job_queue,))
        self.conn.commit()

    # ---------------------------
    # Main Process
    # ---------------------------
    def process(self):
        idno_job_queue = self.start_job_queue()

        # ---- Step 1: Get GRN records ----
        sql_grn = """
            SELECT do.idno, do.compcode, do.recno, do.prdept, do.trantype,
                   do.docno, do.delordno, do.invoiceno, do.suppcode,
                   do.srcdocno, do.po_recno, do.deldept, do.totamount,
                   do.deliverydate, do.trandate, do.trantime, do.recstatus,
                   do.remarks, do.reqdept, do.postdate, sp.name
            FROM material.delordhd do
            LEFT JOIN material.supplier sp
              ON sp.suppcode = do.suppcode
             AND sp.compcode = do.compcode
            WHERE do.compcode = %s
              AND do.trantype = 'GRN'
              AND do.recstatus = 'POSTED'
              -- AND do.trandate >= %s , self.fromdate
              AND do.trandate <= %s
        """
        self.cursor.execute(sql_grn, (self.compcode, self.todate)) 
        grn_list = self.cursor.fetchall()

        # ---- Step 2: Loop through GRNs ----
        for obj in grn_list:
            grn_amt = obj.get("totamount", 0)

            # ---- Step 3: Get GRT (returns) total ----
            sql_grt = """
                SELECT totamount
                FROM material.delordhd
                WHERE compcode = %s
                  AND po_recno = %s
                  AND trantype = 'GRT'
                  AND recstatus = 'POSTED'
                LIMIT 1
            """
            self.cursor.execute(sql_grt, (self.compcode, obj["recno"]))
            row_grt = self.cursor.fetchone()
            grt_amt = row_grt["totamount"] if row_grt else 0

            # ---- Step 4: Get AP Invoice ----
            sql_inv = """
                SELECT apd.amount, aph.postdate
                FROM finance.apacthdr aph
                LEFT JOIN finance.apactdtl apd
                  ON apd.source = aph.source
                 AND apd.trantype = aph.trantype
                 AND apd.auditno = aph.auditno
                 AND apd.compcode = aph.compcode
                WHERE aph.compcode = %s
                  AND aph.document = %s
                  AND aph.recstatus = 'POSTED'
                  AND aph.source = 'AP'
                  AND aph.trantype = 'IN'
                  AND apd.document = %s
                LIMIT 1
            """
            self.cursor.execute(sql_inv, (self.compcode, obj["invoiceno"], obj["delordno"]))
            row_inv = self.cursor.fetchone()
            invoice_amt = row_inv["amount"] if row_inv else 0
            inv_postdate = row_inv["postdate"] if row_inv else None

            # ---- Step 5: Calculate balance ----
            total_bal = grn_amt - grt_amt - invoice_amt
            if round(total_bal, 2) != 0.00:
                pono = "-"
                if obj.get("srcdocno"):
                    pono = f"{obj['reqdept']}-{str(obj['srcdocno']).zfill(7)}"

                grnno = f"{obj['reqdept']}-{str(obj['docno']).zfill(7)}"

                # ---- Step 6: Insert into finance.uninvgrn ----
                sql_insert = """
                    INSERT INTO finance.uninvgrn
                    (compcode, username, job_id, recno, grnno, trandate,
                     pono, delordno, deldept, grn_amt, grt_amt,
                     invoice_amt, total_bal, suppcode, suppname,
                     invoiceno, inv_postdate)
                    VALUES (%s,%s,%s,%s,%s,%s,
                            %s,%s,%s,%s,%s,
                            %s,%s,%s,%s,
                            %s,%s)
                """
                self.cursor.execute(sql_insert, (
                    self.compcode, self.username, idno_job_queue,
                    obj["recno"], grnno, obj["trandate"],
                    pono, obj["delordno"], obj["deldept"],
                    grn_amt, grt_amt, invoice_amt,
                    total_bal, obj["suppcode"], obj["name"],
                    obj["invoiceno"], inv_postdate
                ))

        # ---- Commit all ----
        self.conn.commit()
        self.stop_job_queue(idno_job_queue)

        print("UninvGrnJob: Job Completed")

# ---------------------------
# Example usage
# ---------------------------
if __name__ == "__main__":
    config = configparser.ConfigParser()
    config.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\uninvgrn.ini")

    request = dict(config["DATA1"])
    # print(request)
    job = UninvGrnJob(request)
    job.process()
