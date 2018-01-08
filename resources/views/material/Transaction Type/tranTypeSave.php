<?php
	include_once('../../connection/connect_db.php');
	include_once('../../connection/sschecker.php');
	
	$table='material.ivtxntype';
	
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	$recstatus = 'A';
	
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
				(sysno, compcode, trantype, description, isstype, trbyiv, updqty, crdbfl, updamt,  accttype, recstatus,
				 adduser, adddate, upduser, upddate) 
			VALUES 
				('".clr($_POST['sysno'])."', '$compcode', '".clr($_POST['trantype'])."', '".clr($_POST['description'])."',
				 '".clr($_POST['isstype'])."', '".clr($_POST['trbyiv'])."', '".clr($_POST['updqty'])."', 
				 '".clr($_POST['crdbfl'])."', '".clr($_POST['updamt'])."', '".clr($_POST['accttype'])."', '$recstatus', 
				  '$user', NOW(), '$user', NOW())";	
		
	}else if($_POST['oper']=='edit'){
		
		$sql="UPDATE {$table} SET
				trantype = '".clr($_POST['trantype'])."',
				description = '".clr($_POST['description'])."',
				isstype = '".clr($_POST['isstype'])."',
				accttype = '".clr($_POST['accttype'])."'	
			WHERE 
				sysno='{$_POST['sysno']}'";
				
	}else if($_POST['oper']=='del'){
		$sql="UPDATE {$table} SET  recstatus = 'D'
		 WHERE sysno='{$_POST['id']}'";
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('sysno',$table,clr($_POST['sysno']))){
			throw new Exception('Duplicate key');
		}
		
		if (!$mysqli->query($sql)) {
			throw new Exception($sql);
		}
		
		$mysqli->commit();
		
	}catch( Exception $e ){
		http_response_code(400);
		echo $e->getMessage();
		$mysqli->rollback();
	}
	
	$mysqli->close();
	
?>