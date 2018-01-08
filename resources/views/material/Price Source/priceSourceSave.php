<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.pricesource';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
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
				(compcode,pricecode,description,adduser,adddate,recstatus)
			VALUES 
				('$compcode',  
				'".clr($_POST['pricecode'])."',
				'".clr($_POST['description'])."',  
				'$user', 
				NOW(), 
				'".clr($_POST['recstatus'])."')";
				
	}else if($_POST['oper']=='edit'){
		
		if($_POST['recstatus']=='D')	{
				$sql="UPDATE {$table} SET 	
				upduser = '$user',
				upddate = NOW(),
				deluser= '$user',
				deldate = NOW(),
				recstatus = 'D'
			WHERE 
				compcode = '$compcode' AND pricecode='{$_POST['pricecode']}'";
				
	}
			else {
				$sql="UPDATE {$table} SET
					description = '".clr($_POST['description'])."',
					upduser = '$user',
					upddate = NOW(),
					recstatus = '".clr($_POST['recstatus'])."'
				WHERE 
					compcode = '$compcode' AND pricecode='{$_POST['pricecode']}'";
		}
				//echo "$sql";break;
	}else if($_POST['oper']=='del'){
		 $sql="UPDATE {$table} SET  recstatus = 'D', deluser= '$user', deldate = NOW()
		 WHERE compcode = '$compcode' AND pricecode='{$_POST['id']}'";
		
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('pricecode',$table,clr($_POST['pricecode']))){
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