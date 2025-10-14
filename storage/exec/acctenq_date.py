import pymysql
import random, binascii, copy
import configparser
from datetime import datetime
from typing import List, Dict, Any
import json
import os

class AcctEnqDateJob:
    def __init__(self, request):
        # --- connect DB using PyMySQL ---
        self.conn = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database=request.get("database", "sysdb"),
            cursorclass=pymysql.cursors.DictCursor,
            autocommit=False
        )
        self.cursor = self.conn.cursor()

        now_str = datetime.now().strftime('%Y-%m-%d %I:%M %p')
        self.filename = f"acctenq_dateExport {now_str}.xlsx"

        # request vars
        self.username = request["username"]
        self.compcode = request["compcode"]
        self.glaccount = request["glaccount"]
        self.fromdate = request["fromdate"]  # 'YYYY-MM-DD'
        self.todate = request["todate"]      # 'YYYY-MM-DD'

    def start_job_queue(self, page):
        sql = """
            INSERT INTO sysdb.job_queue
            (compcode, page, filename, adduser, adddate, status, remarks,
             type, date, date_to)
            VALUES (%s,%s,%s,%s,NOW(),'PENDING',%s,%s,%s,%s)
        """
        remarks = f"acctenq_date for account {self.glaccount} from {self.fromdate} to {self.todate}"
        self.cursor.execute(sql, (
            self.compcode, page, self.filename, self.username,
            remarks, self.glaccount, self.fromdate, self.todate
        ))
        self.conn.commit()
        return self.cursor.lastrowid

    def stop_job_queue(self, idno_job_queue):
        sql = "UPDATE sysdb.job_queue SET finishdate=NOW(), status='DONE' WHERE idno=%s"
        self.cursor.execute(sql, (idno_job_queue,))
        self.conn.commit()

    def store_to_db(self, rows: List[Dict[str, Any]], idno_job_queue: int):
        insert_sql = """
            INSERT INTO finance.acctenq_date
            (job_id, id, source, trantype, auditno, lineno_, postdate, description, reference,
             drcostcode, crcostcode, cracc, dracc, amount, acctname_cr, acctname_dr,
             acccode, costcode, costcode_, cramount, dramount, acctname)
            VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,
                    %s,%s,%s,%s,%s,%s,%s)
        """
        for obj in rows:
            # Normalize: ensure each is scalar or None
            params = tuple(
                obj.get(key) if not isinstance(obj.get(key), (dict, list, tuple)) else None
                for key in [
                    "id", "source", "trantype", "auditno", "lineno_", "postdate",
                    "description", "reference", "drcostcode", "crcostcode", "cracc",
                    "dracc", "amount", "acctname_cr", "acctname_dr", "acccode",
                    "costcode", "costcode_", "cramount", "dramount", "acctname"
                ]
            )

            # Add job_id at the front
            params = (idno_job_queue,) + params
            self.cursor.execute(insert_sql, params)

        self.conn.commit()

    # ---- helper "data" functions that mutate obj in-place (return value unused) ----
    def oe_data(self, obj: Dict[str, Any]):
        # modifies description & reference if billsum exists
        sql = """
            SELECT bs.chggroup, ch.description
            FROM debtor.billsum bs
            LEFT JOIN hisdb.chgmast ch
              ON ch.chgcode = bs.chggroup AND ch.compcode = %s
            WHERE bs.compcode = %s AND bs.auditno = %s
            LIMIT 1
        """
        self.cursor.execute(sql, (self.compcode, self.compcode, obj.get("auditno")))
        row = self.cursor.fetchone()
        if row:
            obj["description"] = row.get("description")
            obj["reference"] = f"INV-{obj.get('reference')}"
        # no return required

    def pb_data(self, obj: Dict[str, Any]):
        trantype = obj.get("trantype")
        auditno = obj.get("auditno")
        if trantype == "IN":
            sql = """
                SELECT dbh.payercode, dbm.name
                FROM debtor.dbacthdr dbh
                LEFT JOIN debtor.debtormast dbm
                  ON dbm.debtorcode = dbh.payercode AND dbm.compcode = %s
                WHERE dbh.compcode = %s AND dbh.source='PB' AND dbh.trantype='IN' AND dbh.auditno = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, auditno))
            row = self.cursor.fetchone()
            if row:
                obj["description"] = row.get("payercode")
                obj["reference"] = str(auditno).zfill(7)
        elif trantype == "DN":
            sql = """
                SELECT dbh.payercode, dbm.name
                FROM debtor.dbacthdr dbh
                LEFT JOIN debtor.debtormast dbm
                  ON dbm.debtorcode = dbh.payercode AND dbm.compcode = %s
                WHERE dbh.compcode = %s AND dbh.source='PB' AND dbh.trantype='DN' AND dbh.auditno = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, auditno))
            row = self.cursor.fetchone()
            if row:
                obj["description"] = row.get("payercode")
                obj["reference"] = f"DN-{str(auditno).zfill(7)}"
        elif trantype == "CN":
            sql = """
                SELECT dbh.payercode, dbm.name
                FROM debtor.dbacthdr dbh
                LEFT JOIN debtor.debtormast dbm
                  ON dbm.debtorcode = dbh.payercode AND dbm.compcode = %s
                WHERE dbh.compcode = %s AND dbh.source='PB' AND dbh.trantype='CN' AND dbh.auditno = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, auditno))
            row = self.cursor.fetchone()
            if row:
                obj["description"] = row.get("payercode")
                obj["reference"] = f"CN-{str(auditno).zfill(7)}"
        elif trantype in ("RC", "RD", "RF"):
            # All RC, RD, RF fetch recptno
            sql = """
                SELECT dbh.payercode, dbm.name, dbh.recptno
                FROM debtor.dbacthdr dbh
                LEFT JOIN debtor.debtormast dbm
                  ON dbm.debtorcode = dbh.payercode AND dbm.compcode = %s
                WHERE dbh.compcode = %s AND dbh.source='PB' AND dbh.trantype=%s AND dbh.auditno = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, trantype, auditno))
            row = self.cursor.fetchone()
            if row:
                obj["description"] = row.get("payercode")
                obj["reference"] = row.get("recptno")
        # else: do nothing / return

    def ap_data(self, obj: Dict[str, Any]):
        trantype = obj.get("trantype")
        source = obj.get("source")
        auditno = obj.get("auditno")
        lineno = obj.get("lineno_")
        if trantype == "PD":
            sql = """
                SELECT ap.suppcode, s.name
                FROM finance.apacthdr ap
                LEFT JOIN material.supplier s
                  ON s.suppcode = ap.suppcode AND s.compcode = %s
                WHERE ap.compcode = %s AND ap.source = %s AND ap.trantype=%s AND ap.auditno = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, source, trantype, auditno))
            row = self.cursor.fetchone()
            if row:
                obj["reference"] = row.get("name")
        elif trantype == "AL":
            sql = """
                SELECT ap.suppcode, s.name, ap.remarks
                FROM finance.apalloc ap
                LEFT JOIN material.supplier s
                  ON s.suppcode = ap.suppcode AND s.compcode = %s
                WHERE ap.compcode = %s AND ap.source = %s AND ap.trantype=%s AND ap.auditno = %s AND ap.lineno_ = %s
                LIMIT 1
            """
            self.cursor.execute(sql, (self.compcode, self.compcode, source, trantype, auditno, lineno))
            row = self.cursor.fetchone()
            if row:
                obj["description"] = row.get("remarks")
                obj["reference"] = row.get("name")
        else:
            # return 0 in Laravel; do nothing here
            return

    def cm_data(self, obj: Dict[str, Any]):
        # Laravel returns 0 — do nothing
        return

    def oth_data(self, obj: Dict[str, Any]):
        # just keep description/reference as-is
        return

    # ---- main process ----
    def process(self):
        idno_job_queue = self.start_job_queue("acctenq_date")

        # Build query equivalent to Laravel's query with left joins
        sql = """
            SELECT gl.id, gl.source, gl.trantype, gl.auditno, gl.lineno_, gl.postdate,
                   gl.description, gl.reference, gl.drcostcode, gl.crcostcode,
                   gl.cracc, gl.dracc, gl.amount,
                   glcr.description as acctname_cr, gldr.description as acctname_dr
            FROM finance.gltran gl
            LEFT JOIN finance.glmasref glcr
              ON glcr.glaccno = gl.cracc AND glcr.compcode = %s
            LEFT JOIN finance.glmasref gldr
              ON gldr.glaccno = gl.dracc AND gldr.compcode = %s
            WHERE (gl.dracc = %s OR gl.cracc = %s)
              AND gl.amount != 0
              AND gl.postdate >= %s
              AND gl.postdate <= %s
              AND gl.compcode = %s
            ORDER BY gl.postdate ASC
        """
        params = (
            self.compcode, self.compcode,
            self.glaccount, self.glaccount,
            self.fromdate, self.todate,
            self.compcode
        )
        self.cursor.execute(sql, params)
        rows = self.cursor.fetchall()  # list of dicts

        same_acc = []
        processed_rows = []  # will possibly be appended with same-acc clones later

        for value in rows:
            # Determine debit/credit perspective relative to requested account
            if value.get("dracc") == self.glaccount:
                value["acccode"] = value.get("cracc")
                value["costcode"] = value.get("crcostcode")
                value["costcode_"] = value.get("drcostcode")
                value["cramount"] = 0
                value["dramount"] = value.get("amount")
                value["acctname"] = value.get("acctname_cr")
            else:
                value["acccode"] = value.get("dracc")
                value["costcode"] = value.get("drcostcode")
                value["costcode_"] = value.get("crcostcode")
                value["cramount"] = value.get("amount")
                value["dramount"] = 0
                value["acctname"] = value.get("acctname_dr")

            # collect same-account (dracc == cracc)
            if value.get("dracc") == value.get("cracc"):
                same_acc.append(copy.deepcopy(value))

            # route by source - these functions mutate `value` in-place
            source = value.get("source")
            if source == "OE":
                self.oe_data(value)
            elif source == "PB":
                self.pb_data(value)
            elif source == "AP":
                self.ap_data(value)
            elif source == "CM":
                self.cm_data(value)
            else:
                self.oth_data(value)

            processed_rows.append(value)

        # For same-acc entries: add mirrored record with cramount = amount, dramount = 0
        # for obj in same_acc:
        #     obj["cramount"] = obj.get("amount")
        #     obj["dramount"] = 0
        #     processed_rows.append(obj)

        # txt_filename = f"acctenq_date_{datetime.now().strftime('%Y%m%d_%H%M%S')}.txt"
        # txt_path = os.path.join("D:\\laragon\\www\\msoftweb\\storage\\exec", txt_filename)

        # with open(txt_path, "w", encoding="utf-8") as f:
        #     for row in processed_rows:
        #         f.write(json.dumps(row, default=str) + "\n")

        # store to DB and finish queue
        self.store_to_db(processed_rows, idno_job_queue)
        self.stop_job_queue(idno_job_queue)

        print("AcctEnqDateJob: Job Completed")

if __name__ == "__main__":
    # example usage reading config like your ARAgeing example
    config = configparser.ConfigParser()
    config.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\acctenq_date.ini")  # adjust path/name

    request = dict(config["DATA1"])
    # ensure required keys exist (simple defaults could be applied)
    job = AcctEnqDateJob(request)
    job.process()
