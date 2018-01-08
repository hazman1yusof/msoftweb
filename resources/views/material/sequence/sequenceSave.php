<?php
	include_once('../../connection/connect_db.php');
	include_once('../../connection/sschecker.php');
	
	$table='material.sequence';
	
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
				(compcode, dept, trantype, description, seqno, adduser, adddate, backday, recstatus) 
			VALUES 
				('$compcode','".clr($_POST['dept'])."', '".clr($_POST['trantype'])."', '".clr($_POST['description'])."', '".clr($_POST['seqno'])."', '$user', NOW(), '".clr($_POST['backday'])."', '$recstatus')";
				
	}else if($_POST['oper']=='edit'){
		if($_POST['recstatus']=='D')	{
			$sql="UPDATE {$table} SET
				seqno = '".clr($_POST['seqno'])."',
				delUser = '$user',
				delDate = NOW(),
				backday = '".clr($_POST['backday'])."',
				recstatus = 'D'
				WHERE 
				sysno='{$_POST['sysno']}'";
		}else{
			$sql="UPDATE {$table} SET
				seqno = '".clr($_POST['seqno'])."',
				upduser = '$user',
				upddate = NOW(),
				backday = '".clr($_POST['backday'])."',
				recstatus = '".clr($_POST['recstatus'])."'
				WHERE 
				sysno='{$_POST['sysno']}'";
		}
		
		
				
	}else if($_POST['oper']=='del'){
		 $sql="UPDATE {$table} SET  recstatus = 'D', 
		 delUser = '$user', delDate = NOW()
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