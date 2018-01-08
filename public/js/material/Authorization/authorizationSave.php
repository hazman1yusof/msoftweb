<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
	include_once('../../connection/connect_db.php');
	
	$table='material.authorise';
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
	
	//Turns off auto-committing database modifications
	$mysqli->autocommit(FALSE);  // disable autocommit 'admin',NOW() 
	
	if ($_POST['active'] == 'Yes'){
		$active='1';
	}else {
		$active='0';
	}
	
	
	if($_POST['oper']=='add'){
		
		$sql="INSERT INTO {$table} 
				(compcode,authorid,name,password,deptcode, active, adddate,adduser, upddate,
				upduser) 
			VALUES 
				('".clr($compcode)."',
				'".clr($_POST['authorid'])."',
				'".clr($_POST['name'])."', 
				'".clr($_POST['password'])."',
				'".clr($_POST['deptcode'])."', 
				'$active',  
				NOW(),
				'".clr($user)."', 
				NOW(),
				'".clr($user)."'
				)";
				
		
	}else if($_POST['oper']=='edit'){
		
		$sql="UPDATE {$table} SET
				name = '".clr($_POST['name'])."',
				password = '".clr($_POST['password'])."',
				deptcode ='".clr($_POST['deptcode'])."',
				active = '$active',
				adddate = NOW(), 
				adduser = '".clr($user)."', 
				adddate = NOW(), 
				adduser = '".clr($user)."'
				
				
			WHERE 
				authorid='{$_POST['authorid']}'"; // debtortycode= '".clr($_POST['debtortycode'])."',
				
	}else if($_POST['oper']=='del'){
		$sql="DELETE FROM {$table} WHERE authorid='{$_POST['id']}'";
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('authorid',$table,clr($_POST['authorid']))){
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