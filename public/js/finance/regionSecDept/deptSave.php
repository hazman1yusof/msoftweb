<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
	include_once('../../connection/connect_db.php');
	$table='bank';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	function chgDate($date){
		$newstr=explode("-", $date);
		return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
	}
	
	
	function clr($str){
		global $mysqli;
		return $mysqli->real_escape_string($str);
	}
	
	function duplicate($code,$table,$codetext){
		global $mysqli;
		$sqlDuplicate="select $code from $table where $code = '$codetext'";
		$resultDuplicate=$mysqli->query($sqlDuplicate);
		return $resultDuplicate->num_rows;
	}
	
	$mysqli->autocommit(FALSE);
	
	if($_POST['oper']=='add'){
		
		$sql="INSERT INTO {$table} 
				(compcode,bankcode,bankname,address1,address2, address3, postcode, statecode, country, telno, faxno, contact, bankaccount,
				clearday, effectdate, glaccno, glccode, pctype, lastuser, lastupdate) 
			VALUES 
				('9A', '".clr($_POST['bankcode'])."', '".clr($_POST['bankname'])."', '".clr($_POST['address1'])."', '".clr($_POST['address2'])."', '".clr($_POST['address3'])."', '".clr($_POST['postcode'])."', '".clr($_POST['statecode'])."', '".clr($_POST['country'])."', '".clr($_POST['telno'])."', '".clr($_POST['faxno'])."', '".clr($_POST['contact'])."', '".clr($_POST['bankaccount'])."','".clr($_POST['clearday'])."', '".chgDate($_POST['effectdate'])."', '".clr($_POST['glaccno'])."', '".clr($_POST['glccode'])."','".clr($_POST['pctype'])."', '$user', NOW())";
		
	}else if($_POST['oper']=='edit'){
		
		$sql="UPDATE {$table} SET
				
				compcode = '$compcode',	
				bankname = '".clr($_POST['bankname'])."',
				address1 = '".clr($_POST['address1'])."',
				address2 = '".clr($_POST['address2'])."',
				address3 = '".clr($_POST['address3'])."',
				postcode = '".clr($_POST['postcode'])."',
				statecode = '".clr($_POST['statecode'])."',
				country = '".clr($_POST['country'])."',
				telno = '".clr($_POST['telno'])."',
				faxno = '".clr($_POST['faxno'])."',
				contact = '".clr($_POST['contact'])."',
				bankaccount = '".clr($_POST['bankaccount'])."',
				clearday = '".clr($_POST['clearday'])."',
				effectdate = '".chgDate($_POST['effectdate'])."',
				glaccno = '".clr($_POST['glaccno'])."',
				glccode = '".clr($_POST['glccode'])."',
				pctype = '".clr($_POST['pctype'])."',
				lastuser = '$user',
				lastupdate = NOW()

			WHERE 
				bankcode='{$_POST['bankcode']}'";
				
	}else if($_POST['oper']=='del'){
		$sql="DELETE FROM {$table} WHERE bankcode='{$_POST['bankcode']}'";
		
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('bankcode',$table,clr($_POST['bankcode']))){
			throw new Exception('Duplicate key');
		}
		
		if (!$mysqli->query($sql)) {
			throw new Exception($mysqli->error.'</br>'.$sql);
		}
		
		$mysqli->commit();
		
	}catch( Exception $e ){
		http_response_code(400);
		echo $e->getMessage();
		$mysqli->rollback();
		
	}
	
	$mysqli->close();
	
?>