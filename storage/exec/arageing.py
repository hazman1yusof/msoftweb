import pymysql
import random, binascii
import configparser
from datetime import datetime

class ARAgeingJob:
    def __init__(self, request):
        # --- connect DB using PyMySQL ---
        self.conn = pymysql.connect(
            host="localhost",
            user="root",
            password="",
            database="sysdb",
            cursorclass=pymysql.cursors.DictCursor
        )
        self.cursor = self.conn.cursor()

        now_str = datetime.now().strftime('%Y-%m-%d %I:%M %p')
        if request["type"] == "detail":
            self.filename = f"ARAgeingDetail {now_str}.xlsx"
        else:
            self.filename = f"ARAgeingSummary {now_str}.xlsx"

        self.process = binascii.hexlify(random.randbytes(20)).decode() + ".xlsx"

        self.username = request["username"]
        self.compcode = request["compcode"]
        self.type = request["type"]
        self.date = request["date"]  # yyyy-mm-dd
        self.debtortype = request["debtortype"]
        self.debtorcode_from = (request["debtorcode_from"] or "").upper() or "%"
        self.debtorcode_to = (request["debtorcode_to"] or "").upper()

        # grouping values
        self.groupOne = request["groupone"]
        self.groupTwo = request["grouptwo"]
        self.groupThree = request["groupthree"]
        self.groupFour = request["groupfour"]
        self.groupFive = request["groupfive"]
        self.groupSix = request["groupsix"]
        self.groupby = request["groupby"]

        self.grouping = {0: 0}
        for idx, g in enumerate([
            self.groupOne, self.groupTwo, self.groupThree,
            self.groupFour, self.groupFive, self.groupSix
        ], start=1):
            if g:
                self.grouping[idx] = g

    def start_job_queue(self, page):
        sql = """
            INSERT INTO sysdb.job_queue
            (compcode, page, filename, process, adduser, adddate, status, remarks,
             type, date, debtortype, debtorcode_from, debtorcode_to,
             groupOne, groupTwo, groupThree, groupFour, groupFive, groupSix, groupby)
            VALUES (%s,%s,%s,%s,%s,NOW(),'PENDING',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
        """
        remarks = f"AR Ageing {self.type} as of {self.date}, debtortype: {self.debtortype}, debtorcode from: \"{self.debtorcode_from}\" to \"{self.debtorcode_to}\""
        self.cursor.execute(sql, (
            self.compcode, page, self.filename, self.process, self.username,
            remarks, self.type, self.date, self.debtortype,
            self.debtorcode_from, self.debtorcode_to,
            self.groupOne, self.groupTwo, self.groupThree,
            self.groupFour, self.groupFive, self.groupSix,
            self.groupby
        ))
        self.conn.commit()
        return self.cursor.lastrowid

    def stop_job_queue(self, idno_job_queue):
        sql = "UPDATE sysdb.job_queue SET finishdate=NOW(), status='DONE' WHERE idno=%s"
        self.cursor.execute(sql, (idno_job_queue,))
        self.conn.commit()

    def assign_grouping(self, days):
        group = 0
        for k,v in self.grouping.items():
            if v and days >= int(v):
                group = k
        return group

    def store_to_db(self, array_report, idno_job_queue):
        for obj in array_report:
            sql = """
                INSERT INTO debtor.ARAgeing
                (job_id,idno,source,trantype,auditno,lineno_,amount,outamount,recstatus,
                 entrydate,entrytime,entryuser,reference,recptno,paymode,tillcode,tillno,
                 debtortype,debtorcode,payercode,billdebtor,remark,mrn,episno,authno,expdate,
                 adddate,adduser,upddate,upduser,deldate,deluser,epistype,cbflag,conversion,
                 payername,hdrtype,currency,rate,unit,invno,paytype,bankcharges,RCCASHbalance,
                 RCOSbalance,RCFinalbalance,PymtDescription,orderno,ponum,podate,termdays,termmode,
                 deptcode,posteddate,approvedby,approveddate,pm_name,debtortycode,description,name,
                 unit_desc,doc_no,newamt,`group`)
                VALUES (%(job_id)s,%(idno)s,%(source)s,%(trantype)s,%(auditno)s,%(lineno_)s,%(amount)s,%(outamount)s,%(recstatus)s,
                        %(entrydate)s,%(entrytime)s,%(entryuser)s,%(reference)s,%(recptno)s,%(paymode)s,%(tillcode)s,%(tillno)s,
                        %(debtortype)s,%(debtorcode)s,%(payercode)s,%(billdebtor)s,%(remark)s,%(mrn)s,%(episno)s,%(authno)s,%(expdate)s,
                        %(adddate)s,%(adduser)s,%(upddate)s,%(upduser)s,%(deldate)s,%(deluser)s,%(epistype)s,%(cbflag)s,%(conversion)s,
                        %(payername)s,%(hdrtype)s,%(currency)s,%(rate)s,%(unit)s,%(invno)s,%(paytype)s,%(bankcharges)s,%(RCCASHbalance)s,
                        %(RCOSbalance)s,%(RCFinalbalance)s,%(PymtDescription)s,%(orderno)s,%(ponum)s,%(podate)s,%(termdays)s,%(termmode)s,
                        %(deptcode)s,%(posteddate)s,%(approvedby)s,%(approveddate)s,%(pm_name)s,%(debtortycode)s,%(description)s,%(name)s,
                        %(unit_desc)s,%(doc_no)s,%(newamt)s,%(group)s)
            """
            obj["job_id"] = idno_job_queue
            self.cursor.execute(sql, obj)
        self.conn.commit()

    def process_excel(self):
        idno_job_queue = self.start_job_queue("ARAgeing")

        # Query debtormast and joins
        sql = f"""
            SELECT dh.idno, dh.source, dh.trantype, dh.auditno, dh.lineno_, dh.amount, dh.outamount, dh.recstatus,
                   dh.entrydate, dh.entrytime, dh.entryuser, dh.reference, dh.recptno, dh.paymode, dh.tillcode, dh.tillno,
                   dh.debtorcode, dh.payercode, dh.billdebtor, dh.remark, dh.mrn, dh.episno, dh.authno,
                   dh.expdate, dh.adddate, dh.adduser, dh.upddate, dh.upduser, dh.deldate, dh.deluser, dh.epistype, dh.cbflag,
                   dh.conversion, dh.payername, dh.hdrtype, dh.currency, dh.rate, dh.unit, dh.invno, dh.paytype, dh.bankcharges,
                   dh.RCCASHbalance, dh.RCOSbalance, dh.RCFinalbalance, dh.PymtDescription, dh.orderno, dh.ponum, dh.podate,
                   dh.termdays, dh.termmode, dh.deptcode, dh.posteddate, dh.approvedby, dh.approveddate,
                   pm.Name as pm_name, dm.debtortype, dt.debtortycode, dt.description, dm.name, st.description as unit_desc
            FROM debtor.debtormast dm
            JOIN debtor.debtortype dt ON dt.debtortycode = dm.debtortype AND dt.compcode = %s
            JOIN debtor.dbacthdr dh ON dh.debtorcode = dm.debtorcode AND dh.recstatus='POSTED' AND dh.compcode=%s AND dh.posteddate <= %s AND dh.trantype != %s
            JOIN sysdb.sector st ON st.sectorcode = dh.unit AND st.compcode=%s
            LEFT JOIN hisdb.pat_mast pm ON pm.NewMrn = dh.mrn AND pm.compcode=%s
            WHERE dm.compcode=%s
        """

        params = [self.compcode, self.compcode, self.date, 'RD', self.compcode, self.compcode, self.compcode]

        if self.debtortype.upper() != 'ALL':
            sql += " AND dt.debtortycode=%s"
            params.append(self.debtortype)

        if self.debtorcode_from == self.debtorcode_to:
            sql += " AND dm.debtorcode=%s"
            params.append(self.debtorcode_from)
        elif not self.debtorcode_from and self.debtorcode_to == 'ZZZ':
            pass
        else:
            sql += " AND dm.debtorcode BETWEEN %s AND %s"
            params.extend([self.debtorcode_from, self.debtorcode_to + '%'])

        sql += " ORDER BY dm.debtorcode ASC"

        self.cursor.execute(sql, params)
        debtormast = self.cursor.fetchall()

        array_report = []
        for row in debtormast:
            row["remark"] = ''
            row["doc_no"] = ''
            row["newamt"] = 0

            hdr_amount = row["amount"]
            dt1 = datetime.strptime(self.date, "%Y-%m-%d")
            dt2 = datetime.strptime(str(row["posteddate"]), "%Y-%m-%d")
            days = abs((dt1 - dt2).days)

            row["group"] = self.assign_grouping(days)
            row["days"] = days

            # Calculate newamt depending on trantype
            if row["trantype"] in ("IN", "DN"):
                self.cursor.execute("""
                    SELECT SUM(amount) as alloc_sum FROM debtor.dballoc
                    WHERE compcode=%s AND recstatus='POSTED' AND refsource=%s AND reftrantype=%s AND refauditno=%s AND allocdate <= %s
                """, (self.compcode, row["source"], row["trantype"], row["auditno"], self.date))
                alloc_sum = self.cursor.fetchone()["alloc_sum"] or 0
                newamt = hdr_amount - alloc_sum
            else:
                self.cursor.execute("""
                    SELECT SUM(amount) as doc_sum FROM debtor.dballoc
                    WHERE compcode=%s AND recstatus='POSTED' AND docsource=%s AND doctrantype=%s AND docauditno=%s AND allocdate <= %s
                """, (self.compcode, row["source"], row["trantype"], row["auditno"], self.date))
                doc_sum = self.cursor.fetchone()["doc_sum"] or 0

                self.cursor.execute("""
                    SELECT SUM(amount) as ref_sum FROM debtor.dballoc
                    WHERE compcode=%s AND recstatus='POSTED' AND refsource=%s AND reftrantype=%s AND refauditno=%s AND allocdate <= %s
                """, (self.compcode, row["source"], row["trantype"], row["auditno"], self.date))
                ref_sum = self.cursor.fetchone()["ref_sum"] or 0

                newamt = -(hdr_amount - doc_sum - ref_sum)

            row["newamt"] = newamt

            # Assign doc_no + remark depending on trantype
            trantype = row["trantype"]
            if trantype == "IN":
                row["remark"] = row["pm_name"] if row["mrn"] not in ("0", "") else row["remark"]
                row["doc_no"] = f"{trantype}/{str(row['invno'] or row['auditno']).zfill(7)}"
            elif trantype in ("DN", "CN", "RT", "BC"):
                row["doc_no"] = f"{trantype}/{str(row['auditno']).zfill(7)}"
            elif trantype in ("RF", "RC", "RD"):
                row["doc_no"] = row["recptno"]

            if float(newamt) != 0.0:
                array_report.append(row)

        self.store_to_db(array_report, idno_job_queue)
        self.stop_job_queue(idno_job_queue)

        print("Job Completed")

if __name__ == "__main__":
    config = configparser.ConfigParser()
    config.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\arageing.ini")

    request = dict(config["DATA1"])
    job = ARAgeingJob(request)
    job.process_excel()
