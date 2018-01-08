<?php
	include_once('../../connection/connect_db.php');
	include_once('../../connection/sschecker.php');
	
	$table='material.potype';
	
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
				(compcode, potype, typedec, recstatus, adddate, adduser, upddate, upduser) 
			VALUES 
				('$compcode', '".clr($_POST['potype'])."', '".clr($_POST['typedec'])."', '$recstatus', NOW(), '$user',  NOW(), '$user')";	
		
	}else if($_POST['oper']=='edit'){
		
		$sql="UPDATE {$table} SET
				potype = '".clr($_POST['potype'])."',
				typedec = '".clr($_POST['typedec'])."'
			WHERE 
				potype='{$_POST['potype']}'";
				
	}else if($_POST['oper']=='del'){
		$sql="UPDATE {$table} SET  recstatus = 'D', upddate = NOW(), 
		 upduser= '$user'
		 WHERE potype='{$_POST['id']}'";
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('potype',$table,clr($_POST['potype']))){
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