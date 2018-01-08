<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.deldept';
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
			(compcode,deptcode,description,addr1,addr2,addr3,addr4,tel,fax,generaltel,generalfax,
		      contactper,recstatus,adduser,adddate) 
			VALUES 
				('9A',  
				'".clr($_POST['deptcode'])."', 
				'".clr($_POST['description'])."', 
				'".clr($_POST['addr1'])."', 
				'".clr($_POST['addr2'])."', 
				'".clr($_POST['addr3'])."', 
				'".clr($_POST['addr4'])."', 
				'".clr($_POST['tel'])."',
				'".clr($_POST['fax'])."',
				'".clr($_POST['generaltel'])."',
				'".clr($_POST['generalfax'])."',
				'".clr($_POST['contactper'])."',
				'".clr($_POST['recstatus'])."',
				'$user', 
				NOW())";
				
	}else if($_POST['oper']=='edit'){
		
		$sql="UPDATE {$table} SET
				
				compcode = '$compcode',	
				deptcode = '".clr($_POST['deptcode'])."',
				description = '".clr($_POST['description'])."',
				addr1 = '".clr($_POST['addr1'])."',
				addr2 = '".clr($_POST['addr2'])."',
				addr3 = '".clr($_POST['addr3'])."',
				addr4 = '".clr($_POST['addr4'])."',
				tel = '".clr($_POST['tel'])."',
				fax = '".clr($_POST['fax'])."',
				generaltel = '".clr($_POST['generaltel'])."',
				generalfax = '".clr($_POST['generalfax'])."',
				contactper = '".clr($_POST['contactper'])."',
				recstatus = '".clr($_POST['recstatus'])."',
				adduser = '$user',
				adddate = NOW()

			WHERE 
				deptcode='{$_POST['deptcode']}'";
				
	}else if($_POST['oper']=='del'){
		$sql="DELETE FROM {$table} WHERE deptcode='{$_POST['id']}'";
		
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('deptcode',$table,clr($_POST['deptcode']))){
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