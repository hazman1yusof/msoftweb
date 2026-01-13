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
        self.filename = f"Statement {request['debtorcode_from']} {now_str}.xlsx"

        self.process = binascii.hexlify(random.randbytes(20)).decode() + ".xlsx"

        self.username = request["username"]
        self.compcode = request["compcode"]
        self.type = request["type"]
        self.date = request["date"]  # yyyy-mm-dd
        self.debtortype = request["debtortype"]
        self.debtorcode_from = (request["debtorcode_from"] or "").upper() or "%"
        self.debtorcode_to = (request["debtorcode_to"] or "").upper()

        d = datetime.strptime(self.date, "%Y-%m-%d").date()
        self.first_day = d.replace(day=1)

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
                 unit_desc,doc_no,newamt,`group`,address1,address2,address3,address4,creditterm,creditlimit)
                VALUES (%(job_id)s,%(idno)s,%(source)s,%(trantype)s,%(auditno)s,%(lineno_)s,%(amount)s,%(outamount)s,%(recstatus)s,
                        %(entrydate)s,%(entrytime)s,%(entryuser)s,%(reference)s,%(recptno)s,%(paymode)s,%(tillcode)s,%(tillno)s,
                        %(debtortype)s,%(debtorcode)s,%(payercode)s,%(billdebtor)s,%(remark)s,%(mrn)s,%(episno)s,%(authno)s,%(expdate)s,
                        %(adddate)s,%(adduser)s,%(upddate)s,%(upduser)s,%(deldate)s,%(deluser)s,%(epistype)s,%(cbflag)s,%(conversion)s,
                        %(payername)s,%(hdrtype)s,%(currency)s,%(rate)s,%(unit)s,%(invno)s,%(paytype)s,%(bankcharges)s,%(RCCASHbalance)s,
                        %(RCOSbalance)s,%(RCFinalbalance)s,%(PymtDescription)s,%(orderno)s,%(ponum)s,%(podate)s,%(termdays)s,%(termmode)s,
                        %(deptcode)s,%(posteddate)s,%(approvedby)s,%(approveddate)s,%(pm_name)s,%(debtortycode)s,%(description)s,%(name)s,
                        %(unit_desc)s,%(doc_no)s,%(newamt)s,%(group)s,%(address1)s,%(address2)s,%(address3)s,
                        %(address4)s,%(creditterm)s,%(creditlimit)s)
            """
            obj["job_id"] = idno_job_queue
            self.cursor.execute(sql, obj)
        self.conn.commit()

    def store_to_db_statement(self, rows, job_id):
        FIELDS = [
            'job_id','db1_source','db1_trantype','db1_auditno','db1_lineno_',
            'db1_amount','db1_outamount','db1_recstatus','db1_reference',
            'db1_recptno','db1_paymode','db1_tillcode','db1_tillno',
            'db1_debtortype','db1_debtorcode','db1_payercode','db1_billdebtor',
            'db1_remark','db1_mrn','db1_episno','db1_epistype','db1_cbflag',
            'db1_conversion','db1_payername','db1_currency','db1_rate',
            'db1_unit','db1_invno','db1_orderno','db1_ponum','db1_podate',
            'db1_termdays','db1_termmode','db1_deptcode','db1_posteddate',
            'db2_source','db2_trantype','db2_auditno','db2_lineno_',
            'db2_amount','db2_outamount','db2_recstatus','db2_reference',
            'db2_recptno','db2_paymode','db2_tillcode','db2_tillno',
            'db2_debtortype','db2_debtorcode','db2_payercode','db2_billdebtor',
            'db2_remark','db2_mrn','db2_episno','db2_epistype','db2_cbflag',
            'db2_conversion','db2_payername','db2_currency','db2_rate',
            'db2_unit','db2_invno','db2_orderno','db2_ponum','db2_podate',
            'db2_termdays','db2_termmode','db2_deptcode','db2_posteddate',
            'da_docsource','da_doctrantype','da_docauditno',
            'da_refsource','da_reftrantype','da_refauditno','da_allocamount',
            'pm_name'
        ]

        sql = """
            INSERT INTO debtor.statement
            (job_id,db1_source,db1_trantype,db1_auditno,db1_lineno_,db1_amount,db1_outamount,db1_recstatus,db1_reference,
                db1_recptno,db1_paymode,db1_tillcode,db1_tillno,db1_debtortype,db1_debtorcode,db1_payercode,db1_billdebtor,
                db1_remark,db1_mrn,db1_episno,db1_epistype,db1_cbflag,db1_conversion,db1_payername,db1_currency,db1_rate,
                db1_unit,db1_invno,db1_orderno,db1_ponum,db1_podate,db1_termdays,db1_termmode,db1_deptcode,db1_posteddate,
                db2_source,db2_trantype,db2_auditno,db2_lineno_,db2_amount,db2_outamount,db2_recstatus,db2_reference,
                db2_recptno,db2_paymode,db2_tillcode,db2_tillno,db2_debtortype,db2_debtorcode,db2_payercode,db2_billdebtor,
                db2_remark,db2_mrn,db2_episno,db2_epistype,db2_cbflag,db2_conversion,db2_payername,db2_currency,db2_rate,
                db2_unit,db2_invno,db2_orderno,db2_ponum,db2_podate,db2_termdays,db2_termmode,db2_deptcode,db2_posteddate,
                da_docsource,da_doctrantype,da_docauditno,da_refsource,da_reftrantype,da_refauditno,da_allocamount,pm_name)
            VALUES (%(job_id)s,%(db1_source)s,%(db1_trantype)s,%(db1_auditno)s,%(db1_lineno_)s,%(db1_amount)s,
                %(db1_outamount)s,%(db1_recstatus)s,%(db1_reference)s,%(db1_recptno)s,%(db1_paymode)s,%(db1_tillcode)s,
                %(db1_tillno)s,%(db1_debtortype)s,%(db1_debtorcode)s,%(db1_payercode)s,%(db1_billdebtor)s,%(db1_remark)s,
                %(db1_mrn)s,%(db1_episno)s,%(db1_epistype)s,%(db1_cbflag)s,%(db1_conversion)s,%(db1_payername)s,
                %(db1_currency)s,%(db1_rate)s,%(db1_unit)s,%(db1_invno)s,%(db1_orderno)s,%(db1_ponum)s,%(db1_podate)s,
                %(db1_termdays)s,%(db1_termmode)s,%(db1_deptcode)s,%(db1_posteddate)s,%(db2_source)s,%(db2_trantype)s,
                %(db2_auditno)s,%(db2_lineno_)s,%(db2_amount)s,%(db2_outamount)s,%(db2_recstatus)s,%(db2_reference)s,
                %(db2_recptno)s,%(db2_paymode)s,%(db2_tillcode)s,%(db2_tillno)s,%(db2_debtortype)s,%(db2_debtorcode)s,
                %(db2_payercode)s,%(db2_billdebtor)s,%(db2_remark)s,%(db2_mrn)s,%(db2_episno)s,%(db2_epistype)s,
                %(db2_cbflag)s,%(db2_conversion)s,%(db2_payername)s,%(db2_currency)s,%(db2_rate)s,%(db2_unit)s,
                %(db2_invno)s,%(db2_orderno)s,%(db2_ponum)s,%(db2_podate)s,%(db2_termdays)s,%(db2_termmode)s,
                %(db2_deptcode)s,%(db2_posteddate)s,%(da_docsource)s,%(da_doctrantype)s,%(da_docauditno)s,%(da_refsource)s,
                %(da_reftrantype)s,%(da_refauditno)s,%(da_allocamount)s,%(pm_name)s)
        """
        data = []
        for obj in rows:
            row = dict(obj)               # SAFE COPY
            row["job_id"] = job_id
            for f in FIELDS:
                row.setdefault(f, None)
            data.append(row)
            
        self.cursor.executemany(sql, data)
        self.conn.commit()

    def process_excel(self):
        idno_job_queue = self.start_job_queue("ARStatement")

        # Query debtormast and joins
        sql = f"""
            SELECT dh.idno, dh.source, dh.trantype, dh.auditno, dh.lineno_, dh.amount, dh.outamount, dh.recstatus,
                   dh.entrydate, dh.entrytime, dh.entryuser, dh.reference, dh.recptno, dh.paymode, dh.tillcode, dh.tillno,
                   dh.debtorcode, dh.payercode, dh.billdebtor, dh.remark, dh.mrn, dh.episno, dh.authno,
                   dh.expdate, dh.adddate, dh.adduser, dh.upddate, dh.upduser, dh.deldate, dh.deluser, dh.epistype, dh.cbflag,
                   dh.conversion, dh.payername, dh.hdrtype, dh.currency, dh.rate, dh.unit, dh.invno, dh.paytype, dh.bankcharges,
                   dh.RCCASHbalance, dh.RCOSbalance, dh.RCFinalbalance, dh.PymtDescription, dh.orderno, dh.ponum, dh.podate,
                   dh.termdays, dh.termmode, dh.deptcode, dh.posteddate, dh.approvedby, dh.approveddate,
                   pm.Name as pm_name, dm.debtortype, dt.debtortycode, dt.description, dm.name, st.description as unit_desc,
                   dm.address1, dm.address2, dm.address3, dm.address4, dm.creditterm, dm.creditlimit
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
                row["remark"] = row["reference"]
            elif trantype in ("RF", "RC", "RD"):
                row["doc_no"] = row["recptno"]
                row["remark"] = row["recptno"]

            if float(newamt) != 0.0:
                array_report.append(row)

        self.store_to_db(array_report, idno_job_queue)

        sql = f"""
            SELECT
                db1.source       AS db1_source,
                db1.trantype     AS db1_trantype,
                db1.auditno      AS db1_auditno,
                db1.lineno_      AS db1_lineno_,
                db1.amount       AS db1_amount,
                db1.outamount    AS db1_outamount,
                db1.recstatus    AS db1_recstatus,
                db1.reference    AS db1_reference,
                db1.recptno      AS db1_recptno,
                db1.paymode      AS db1_paymode,
                db1.tillcode     AS db1_tillcode,
                db1.tillno       AS db1_tillno,
                db1.debtortype   AS db1_debtortype,
                db1.debtorcode   AS db1_debtorcode,
                db1.payercode    AS db1_payercode,
                db1.billdebtor   AS db1_billdebtor,
                db1.remark       AS db1_remark,
                db1.mrn          AS db1_mrn,
                db1.episno       AS db1_episno,
                db1.epistype     AS db1_epistype,
                db1.cbflag       AS db1_cbflag,
                db1.conversion   AS db1_conversion,
                db1.payername    AS db1_payername,
                db1.currency     AS db1_currency,
                db1.rate         AS db1_rate,
                db1.unit         AS db1_unit,
                db1.invno        AS db1_invno,
                db1.orderno      AS db1_orderno,
                db1.ponum        AS db1_ponum,
                db1.podate       AS db1_podate,
                db1.termdays     AS db1_termdays,
                db1.termmode     AS db1_termmode,
                db1.deptcode     AS db1_deptcode,
                db1.posteddate   AS db1_posteddate,

                db2.source       AS db2_source,
                db2.trantype     AS db2_trantype,
                db2.auditno      AS db2_auditno,
                db2.lineno_      AS db2_lineno_,
                db2.amount       AS db2_amount,
                db2.outamount    AS db2_outamount,
                db2.recstatus    AS db2_recstatus,
                db2.reference    AS db2_reference,
                db2.recptno      AS db2_recptno,
                db2.paymode      AS db2_paymode,
                db2.tillcode     AS db2_tillcode,
                db2.tillno       AS db2_tillno,
                db2.debtortype   AS db2_debtortype,
                db2.debtorcode   AS db2_debtorcode,
                db2.payercode    AS db2_payercode,
                db2.billdebtor   AS db2_billdebtor,
                db2.remark       AS db2_remark,
                db2.mrn          AS db2_mrn,
                db2.episno       AS db2_episno,
                db2.epistype     AS db2_epistype,
                db2.cbflag       AS db2_cbflag,
                db2.conversion   AS db2_conversion,
                db2.payername    AS db2_payername,
                db2.currency     AS db2_currency,
                db2.rate         AS db2_rate,
                db2.unit         AS db2_unit,
                db2.invno        AS db2_invno,
                db2.orderno      AS db2_orderno,
                db2.ponum        AS db2_ponum,
                db2.podate       AS db2_podate,
                db2.termdays     AS db2_termdays,
                db2.termmode     AS db2_termmode,
                db2.deptcode     AS db2_deptcode,
                db2.posteddate   AS db2_posteddate,

                da.docsource     AS da_docsource,
                da.doctrantype   AS da_doctrantype,
                da.docauditno    AS da_docauditno,
                da.refsource     AS da_refsource,
                da.reftrantype   AS da_reftrantype,
                da.refauditno    AS da_refauditno,
                da.amount        AS da_allocamount,

                pm.Name          AS pm_name
            FROM debtor.dbacthdr db1
            JOIN debtor.dballoc da
                ON da.docsource = db1.source
               AND da.doctrantype = db1.trantype
               AND da.docauditno = db1.auditno
               AND da.compcode = %s
            JOIN debtor.dbacthdr db2
                ON db2.source = da.refsource
               AND db2.trantype = da.reftrantype
               AND db2.auditno = da.refauditno
               AND db2.compcode = %s
            LEFT JOIN hisdb.pat_mast pm
                ON pm.NewMrn = db2.mrn
               AND pm.compcode = %s
            WHERE db1.compcode = %s
              AND db1.source = 'PB'
              AND db1.trantype = 'RC'
              AND db1.debtorcode BETWEEN %s AND %s
              AND db1.recstatus = 'POSTED'
              AND DATE(db1.posteddate) BETWEEN %s AND %s
        """
        params = (
            self.compcode,
            self.compcode,
            self.compcode,
            self.compcode,
            self.debtorcode_from,
            self.debtorcode_to,
            self.first_day,
            self.date
        )

        self.cursor.execute(sql, params)
        statement = self.cursor.fetchall()

        self.store_to_db_statement(statement, idno_job_queue)
        self.stop_job_queue(idno_job_queue)

        print("Job Completed")

if __name__ == "__main__":
    config = configparser.ConfigParser()
    config.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\statement.ini")

    request = dict(config["DATA1"])
    job = ARAgeingJob(request)
    job.process_excel()
