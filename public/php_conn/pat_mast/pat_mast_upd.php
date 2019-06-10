<?php
	
	include("../config.php"); 
	include("../conn.php"); 

	$episode_array = parse_ini_file("pat_mast.ini");

	// $prepare="INSERT INTO hisdb.episode (compcode,mrn,episno,admsrccode,epistycode,case_code,ward,bedtype,room,bed,admdoctor,attndoctor,refdoctor,prescribedays,pay_type,pyrmode,climitauthid,crnumber,depositreq,deposit,pkgcode,billtype,remarks,episstatus,episactive,adddate,adduser,reg_date,reg_time,dischargedate,dischargeuser,dischargetime,dischargedest,allocdoc,allocbed,allocnok,allocpayer,allocicd,lastupdate,lastuser,lasttime,procode,dischargediag,lodgerno,regdept,diet1,diet2,diet3,diet4,diet5,glauthid,treatcode,diagcode,complain,diagfinal,clinicalnote,conversion,newcaseP,newcaseNP,followupP,followupNP,bed2,bed3,bed4,bed5,bed6,bed7,bed8,bed9,bed10,diagprov,visitcase,PkgAutoNo,AgreementID,AdminFees,EDDept) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$arrayValue = [];
	$quesmark_arr = [];
	$column = implode(",",array_keys($episode_array));

	foreach ($episode_array as $key => $value){
		array_push($quesmark_arr,'?');
		array_push($arrayValue,$value);
	}

	$quesmark = implode(",",$quesmark_arr);

	$prepare = "INSERT INTO hisdb.pat_mast (".$column.") VALUES (".$quesmark.")";


	////readable syntax
	$readable = $prepare;
	foreach($arrayValue as $val){
		$readable=preg_replace("/\?/", "'".$val."'", $readable,1);
	}
	echo $readable;

	// $sth=$conn->prepare($prepare);
	// $result = $sth->execute($arrayValue) or die(print_r($sth->errorInfo(), true));

		
?>