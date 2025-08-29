import mysql.connector
import json
import random, binascii
import configparser
from datetime import datetime

class ARAgeingCollE_Report_job:

    def __init__(self, request):
        self.filename = f"ARAgeingCollection {datetime.now().strftime('%Y-%m-%d %I:%M %p')}.xlsx"
        self.process = binascii.hexlify(random.randbytes(20)).decode() + ".xlsx"

        self.username = request["username"]
        self.compcode = request["compcode"]
        self.date_from = request["date_from"]
        self.date_to = request["date_to"]

        self.debtorcode_from = request["debtorcode_from"]
        self.debtorcode_to = request["debtorcode_to"]

        # grouping values
        self.groupOne = request["groupOne"]
        self.groupTwo = request["groupTwo"]
        self.groupThree = request["groupThree"]
        self.groupFour = request["groupFour"]
        self.groupFive = request["groupFive"]
        self.groupSix = request["groupSix"]
        self.groupby = request["groupby"]

        self.grouping = {0: 0}
        for idx, g in enumerate([
            self.groupOne, self.groupTwo, self.groupThree,
            self.groupFour, self.groupFive, self.groupSix
        ], start=1):
            if g:
                self.grouping[idx] = g

        # --- connect DB ---
        self.conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="sysdb"
        )
        self.cursor = self.conn.cursor(dictionary=True)

    def assign_grouping(self, days):
        group = 0
        for key, value in self.grouping.items():
            if value and days >= int(value):
                group = key
        return group

    def start_job_queue(self, page):
        sql = """
        INSERT INTO sysdb.job_queue
        (compcode,page,filename,process,adduser,adddate,status,remarks,type,date,date_to,
         debtortype,debtorcode_from,debtorcode_to,groupOne,groupTwo,groupThree,groupFour,
         groupFive,groupSix,groupby)
        VALUES (%s,%s,%s,%s,%s,NOW(),'PENDING',%s,'-',%s,%s,'-',%s,%s,
                %s,%s,%s,%s,%s,%s,%s)
        """
        remarks = f"AR Ageing Collection as of {self.date_from}, debtorcode from: {self.debtorcode_from} to {self.debtorcode_to}"
        params = (self.compcode, page, self.filename, self.process, self.username, remarks,
                  self.date_from, self.date_to, self.debtorcode_from, self.debtorcode_to,
                  self.groupOne, self.groupTwo, self.groupThree, self.groupFour,
                  self.groupFive, self.groupSix, self.groupby)
        self.cursor.execute(sql, params)
        self.conn.commit()
        return self.cursor.lastrowid

    def stop_job_queue(self, idno_job_queue):
        sql = """UPDATE sysdb.job_queue 
                 SET finishdate=NOW(), status='DONE' 
                 WHERE idno=%s"""
        self.cursor.execute(sql, (idno_job_queue,))
        self.conn.commit()

    def store_to_db(self, array_report_1, array_report_2, idno_job_queue):
      # ---- Process group sums ----
      for ar_1 in array_report_1:
          ar_1["groupOne"] = 0
          ar_1["groupTwo"] = 0
          ar_1["groupThree"] = 0
          ar_1["groupFour"] = 0
          ar_1["groupFive"] = 0
          ar_1["groupSix"] = 0

          for ar_2 in array_report_2:
              if ar_2["link_idno"] == ar_1["idno"]:
                  if ar_2["group"] == 0:
                      ar_1["groupOne"] += ar_2["newamt"]
                  elif ar_2["group"] == 1:
                      ar_1["groupTwo"] += ar_2["newamt"]
                  elif ar_2["group"] == 2:
                      ar_1["groupThree"] += ar_2["newamt"]
                  elif ar_2["group"] == 3:
                      ar_1["groupFour"] += ar_2["newamt"]
                  elif ar_2["group"] == 4:
                      ar_1["groupFive"] += ar_2["newamt"]
                  elif ar_2["group"] == 5:
                      ar_1["groupSix"] += ar_2["newamt"]

      # ---- Insert into MySQL ----
      sql = """
          INSERT INTO debtor.arageing (
              job_id, idno, source, trantype, auditno, lineno_, amount, outamount,
              recstatus, entrydate, entrytime, entryuser, reference, recptno, paymode,
              tillcode, tillno, debtortype, debtorcode, payercode, billdebtor, remark,
              mrn, episno, authno, expdate, adddate, adduser, upddate, upduser,
              deldate, deluser, epistype, cbflag, conversion, payername, hdrtype,
              currency, rate, unit, invno, paytype, bankcharges, RCCASHbalance,
              RCOSbalance, RCFinalbalance, PymtDescription, orderno, ponum, podate,
              termdays, termmode, deptcode, posteddate, approvedby, approveddate,
              pm_name, name, unit_desc, doc_no, newamt, `group`, group_type,
              punallocamt, link_idno, groupOne, groupTwo, groupThree, groupFour,
              groupFive, groupSix
          ) VALUES (
              %(job_id)s, %(idno)s, %(source)s, %(trantype)s, %(auditno)s, %(lineno_)s,
              %(amount)s, %(outamount)s, %(recstatus)s, %(entrydate)s, %(entrytime)s,
              %(entryuser)s, %(reference)s, %(recptno)s, %(paymode)s, %(tillcode)s,
              %(tillno)s, %(debtortype)s, %(debtorcode)s, %(payercode)s, %(billdebtor)s,
              %(remark)s, %(mrn)s, %(episno)s, %(authno)s, %(expdate)s, %(adddate)s,
              %(adduser)s, %(upddate)s, %(upduser)s, %(deldate)s, %(deluser)s,
              %(epistype)s, %(cbflag)s, %(conversion)s, %(payername)s, %(hdrtype)s,
              %(currency)s, %(rate)s, %(unit)s, %(invno)s, %(paytype)s, %(bankcharges)s,
              %(RCCASHbalance)s, %(RCOSbalance)s, %(RCFinalbalance)s, %(PymtDescription)s,
              %(orderno)s, %(ponum)s, %(podate)s, %(termdays)s, %(termmode)s, %(deptcode)s,
              %(posteddate)s, %(approvedby)s, %(approveddate)s, %(pm_name)s, %(name)s,
              %(unit_desc)s, %(doc_no)s, %(newamt)s, %(group)s, %(group_type)s,
              %(punallocamt)s, %(link_idno)s, %(groupOne)s, %(groupTwo)s, %(groupThree)s,
              %(groupFour)s, %(groupFive)s, %(groupSix)s
          )
      """

      for obj in array_report_1:
          obj["job_id"] = idno_job_queue
          self.cursor.execute(sql, obj)
      self.conn.commit()

    def run(self):
        idno_job_queue = self.start_job_queue("ARAgeingColl")

        # ---- Main debtormast query ----
        sql = """
        SELECT dh.idno, dh.source, dh.trantype, dh.auditno, dh.lineno_, dh.amount, dh.outamount,
               dh.recstatus, dh.entrydate, dh.entrytime, dh.entryuser, dh.reference, dh.recptno,
               dh.paymode, dh.tillcode, dh.tillno, dh.debtortype, dh.debtorcode, dh.payercode,
               dh.billdebtor, dh.remark, dh.mrn, dh.episno, dh.authno, dh.expdate, dh.adddate,
               dh.adduser, dh.upddate, dh.upduser, dh.deldate, dh.deluser, dh.epistype, dh.cbflag,
               dh.conversion, dh.payername, dh.hdrtype, dh.currency, dh.rate, dh.unit, dh.invno,
               dh.paytype, dh.bankcharges, dh.RCCASHbalance, dh.RCOSbalance, dh.RCFinalbalance,
               dh.PymtDescription, dh.orderno, dh.ponum, dh.podate, dh.termdays, dh.termmode,
               dh.deptcode, dh.posteddate, dh.approvedby, dh.approveddate,
               pm.Name AS pm_name, dm.debtortype, dm.name, st.description AS unit_desc
        FROM debtor.debtormast AS dm
        JOIN debtor.dbacthdr AS dh
            ON dh.debtorcode = dm.debtorcode
           AND dh.posteddate >= %s
           AND dh.posteddate <= %s
           AND dh.trantype IN ('RC','RD')
           AND dh.recstatus = 'POSTED'
           AND dh.compcode = %s
        JOIN sysdb.sector AS st
            ON st.sectorcode = dh.unit
           AND st.compcode = %s
        LEFT JOIN hisdb.pat_mast AS pm
            ON pm.NewMrn = dh.mrn
           AND pm.compcode = %s
        WHERE dm.compcode = %s
        """
        params = [self.date_from, self.date_to, self.compcode,
                  self.compcode, self.compcode, self.compcode]

        if self.debtorcode_from == self.debtorcode_to:
            sql += " AND dm.debtorcode = %s"
            params.append(self.debtorcode_from)
        elif not self.debtorcode_from and self.debtorcode_to == "ZZZ":
            pass
        else:
            sql += " AND dm.debtorcode BETWEEN %s AND %s"
            params.append(self.debtorcode_from)
            params.append(self.debtorcode_to + "%")

        sql += " ORDER BY dm.debtorcode ASC"

        self.cursor.execute(sql, params)
        debtormast = self.cursor.fetchall()

        array_report_1 = []
        array_report_2 = []

        for value in debtormast:
            value["remark"] = ""
            value["doc_no"] = ""
            value["newamt"] = value["amount"]
            value["group"] = ""
            value["group_type"] = 1
            value["days"] = ""
            value["link_idno"] = ""
            value["groupOne"] = ""
            value["groupTwo"] = ""
            value["groupThree"] = ""
            value["groupFour"] = ""
            value["groupFive"] = ""
            value["groupSix"] = ""
            punallocamt = value["amount"]

            # ---- allocations (dballoc) ----
            self.cursor.execute("""
                SELECT * FROM debtor.dballoc da
                WHERE da.compcode=%s AND da.recstatus='POSTED'
                AND da.docsource=%s AND da.doctrantype=%s 
                AND da.docauditno=%s AND da.allocdate <= %s
            """, (self.compcode, value["source"], value["trantype"], value["auditno"], self.date_to))
            dballoc = self.cursor.fetchall()

            for obj_dballoc in dballoc:
                punallocamt -= obj_dballoc["amount"]

                # ---- reference dbacthdr (ref_db) ----
                self.cursor.execute("""
                    SELECT dh.idno, dh.source, dh.trantype, dh.auditno, dh.lineno_, dh.amount, dh.outamount,
                           dh.recstatus, dh.entrydate, dh.entrytime, dh.entryuser, dh.reference, dh.recptno,
                           dh.paymode, dh.tillcode, dh.tillno, dh.debtortype, dh.debtorcode, dh.payercode,
                           dh.billdebtor, dh.remark, dh.mrn, dh.episno, dh.authno, dh.expdate, dh.adddate,
                           dh.adduser, dh.upddate, dh.upduser, dh.deldate, dh.deluser, dh.epistype, dh.cbflag,
                           dh.conversion, dh.payername, dh.hdrtype, dh.currency, dh.rate, dh.unit, dh.invno,
                           dh.paytype, dh.bankcharges, dh.RCCASHbalance, dh.RCOSbalance, dh.RCFinalbalance,
                           dh.PymtDescription, dh.orderno, dh.ponum, dh.podate, dh.termdays, dh.termmode,
                           dh.deptcode, dh.posteddate, dh.approvedby, dh.approveddate,
                           pm.Name AS pm_name, dm.debtortype, dm.name, st.description AS unit_desc
                    FROM debtor.debtormast AS dm
                    JOIN debtor.dbacthdr AS dh
                        ON dh.debtorcode = dm.debtorcode
                       AND dh.source = %s
                       AND dh.trantype = %s
                       AND dh.auditno = %s
                       AND dh.recstatus = 'POSTED'
                       AND dh.compcode = %s
                    JOIN sysdb.sector AS st
                        ON st.sectorcode = dh.unit
                       AND st.compcode = %s
                    LEFT JOIN hisdb.pat_mast AS pm
                        ON pm.NewMrn = dh.mrn
                       AND pm.compcode = %s
                    WHERE dm.compcode = %s
                """, (obj_dballoc["refsource"], obj_dballoc["reftrantype"], obj_dballoc["refauditno"],
                      self.compcode, self.compcode, self.compcode, self.compcode))
                ref_db = self.cursor.fetchall()

                for obj_ref_db in ref_db:
                    dt1 = datetime.strptime(self.date_to, "%Y-%m-%d")
                    dt2 = datetime.strptime(str(obj_ref_db["posteddate"]), "%Y-%m-%d")
                    days = abs((dt1 - dt2).days)

                    obj_ref_db["remark"] = obj_ref_db.get("remark", "")
                    obj_ref_db["doc_no"] = obj_ref_db.get("invno")
                    obj_ref_db["newamt"] = obj_ref_db["amount"]
                    obj_ref_db["group"] = self.assign_grouping(days)
                    obj_ref_db["group_type"] = 2
                    obj_ref_db["days"] = days
                    obj_ref_db["punallocamt"] = ""
                    obj_ref_db["link_idno"] = value["idno"]

                    array_report_2.append(obj_ref_db)

            value["punallocamt"] = punallocamt
            array_report_1.append(value)

        # ---- Save into DB ----
        self.store_to_db(array_report_1, array_report_2, idno_job_queue)
        self.stop_job_queue(idno_job_queue)

        print("Job Completed")

    pass

if __name__ == "__main__":
  config = configparser.ConfigParser()
  config.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\arageingcollection.ini")

  # Convert section DATA1 into dict (similar to Laravel $request)
  request = dict(config["DATA1"])

  job = ARAgeingCollE_Report_job(request)
  job.run()
