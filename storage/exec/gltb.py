import pymysql
import configparser
from datetime import datetime

def truncate_(mycursor, year, period):
    query = ("UPDATE recondb.gltb SET amount = 0 "
             "WHERE compcode = %s and year = %s and period = %s")
    mycursor.execute(query, [compcode_, year, period])

    query5 = ("UPDATE finance.glmasdtl SET actamount" + period_ +
              " = %s WHERE compcode = %s and year= %s")
    mycursor.execute(query5, [0, compcode_, year_])
    mydb.commit()

def start_job_queue(mycursor,page,username_,compcode_,year_,period_):
    sql = """
    INSERT INTO sysdb.job_queue
    (compcode,page,filename,process,adduser,adddate,status,date)
    VALUES (%s,%s,%s,%s,%s,NOW(),'PENDING',%s)
    """
    params = (compcode_, page, '-', '-', username_,year_+'-'+period_+'-01')
    mycursor.execute(sql, params)
    mydb.commit()
    return mycursor.lastrowid

def stop_job_queue(mycursor,idno_job_queue):
    sql = """UPDATE sysdb.job_queue 
             SET finishdate=NOW(), status='DONE' 
             WHERE idno=%s"""
    mycursor.execute(sql, (idno_job_queue,))
    mydb.commit()

# --- Load configuration ---
db_conf = configparser.RawConfigParser()
db_conf.read("D:\\laragon\\www\\msoftweb\\storage\\exec\\gltb.ini")

username_ = db_conf.get('DATA1', 'username', raw=False)
compcode_ = db_conf.get('DATA1', 'compcode', raw=False)
year_ = db_conf.get('DATA1', 'year', raw=False)
period_ = db_conf.get('DATA1', 'period', raw=False)

# --- Connect using pymysql ---
mydb = pymysql.connect(
    host='localhost',
    user='root',
    password='',   # pymysql uses "password" instead of "passwd"
    database='recondb',
    cursorclass=pymysql.cursors.Cursor   # tuple-style results (same as mysql.connector default)
)

mycursor = mydb.cursor()

datenow = datetime.today().strftime('%Y-%m-%d')
timestart = datetime.today().strftime('%Y-%m-%d %H:%M:%S')

idno_job_queue = start_job_queue(mycursor,'gltb',username_,compcode_,year_,period_)
truncate_(mycursor, year_, period_)

# --- Step 1: process finance.gltran ---
query = ("SELECT crcostcode, cracc, drcostcode, dracc, year, period, amount "
         "FROM finance.gltran WHERE compcode = %s and year= %s and period = %s")
mycursor.execute(query, [compcode_, year_, period_])
myresult = mycursor.fetchall()

line = 1
for (crcostcode, cracc, drcostcode, dracc, year, period, amount) in myresult:

    # debit
    if drcostcode is not None:
        query2 = ("SELECT amount FROM recondb.gltb WHERE compcode = %s and year= %s "
                  "and period = %s and costcode = %s and glaccount = %s")
        mycursor.execute(query2, [compcode_, year, period, drcostcode, dracc])
        res_gltb = mycursor.fetchone()

        if res_gltb is None:
            newamount = amount
            query3 = ("INSERT INTO recondb.gltb (compcode,costcode,glaccount,year,period,amount) "
                      "VALUES (%s,%s,%s,%s,%s,%s)")
            mycursor.execute(query3, [compcode_, drcostcode, dracc, year, period, newamount])
        else:
            newamount = res_gltb[0] + amount
            query4 = ("UPDATE recondb.gltb SET amount = %s "
                      "WHERE compcode = %s and year= %s and period = %s and costcode = %s and glaccount = %s")
            mycursor.execute(query4, [newamount, compcode_, year, period, drcostcode, dracc])

    # credit
    if crcostcode is not None:
        query2 = ("SELECT amount FROM recondb.gltb WHERE compcode = %s and year= %s "
                  "and period = %s and costcode = %s and glaccount = %s")
        mycursor.execute(query2, [compcode_, year, period, crcostcode, cracc])
        res_gltb = mycursor.fetchone()

        if res_gltb is None:
            newamount = -amount
            query3 = ("INSERT INTO recondb.gltb (compcode,costcode,glaccount,year,period,amount) "
                      "VALUES (%s,%s,%s,%s,%s,%s)")
            mycursor.execute(query3, [compcode_, crcostcode, cracc, year, period, newamount])
        else:
            newamount = res_gltb[0] - amount
            query4 = ("UPDATE recondb.gltb SET amount = %s "
                      "WHERE compcode = %s and year= %s and period = %s and costcode = %s and glaccount = %s")
            mycursor.execute(query4, [newamount, compcode_, year, period, crcostcode, cracc])

    print(line)
    mydb.commit()
    line += 1

# --- Step 2: update finance.glmasdtl ---
query6 = ("SELECT amount, costcode, glaccount, year, period "
          "FROM recondb.gltb WHERE compcode = %s and year= %s and period = %s "
          "and costcode is not null and glaccount is not null")
mycursor.execute(query6, [compcode_, year_, period_])
myresult6 = mycursor.fetchall()

line = 1
for (amount, costcode, glaccount, year, period) in myresult6:

    query7 = ("SELECT glaccount FROM finance.glmasdtl "
              "WHERE compcode = %s and year= %s and costcode = %s and glaccount = %s")
    mycursor.execute(query7, [compcode_, year, costcode, glaccount])
    res_query7 = mycursor.fetchone()

    if res_query7 is None:
        query3 = ("INSERT INTO finance.glmasdtl "
                  "(compcode,costcode,glaccount,year,recstatus,adduser,adddate,actamount" + period_ + ") "
                  "VALUES (%s,%s,%s,%s,%s,%s,%s,%s)")
        mycursor.execute(query3, [compcode_, costcode, glaccount, year_, 'ACTIVE', 'SYSTEM_AR', datenow, amount])
    else:
        query4 = ("UPDATE finance.glmasdtl SET actamount" + period_ +
                  " = %s WHERE compcode = %s and year= %s and costcode = %s and glaccount = %s")
        mycursor.execute(query4, [amount, compcode_, year_, costcode, glaccount])

    print(f"{line} - glmasdtl account: {glaccount} costcode: {costcode}")
    mydb.commit()
    line += 1

stop_job_queue(mycursor,idno_job_queue)

mycursor.close()
mydb.close()

timeend = datetime.today().strftime('%Y-%m-%d %H:%M:%S')
print('time start : ' + timestart)
print('time end : ' + timeend)
