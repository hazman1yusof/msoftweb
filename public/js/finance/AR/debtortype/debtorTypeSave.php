<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
	include_once('../../connection/connect_db.php');
	$table='debtor.debtortype';
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
	
	//Turns off auto-committing database modifications
	$mysqli->autocommit(FALSE);  // disable autocommit 'admin',NOW() 
	
	if($_POST['oper']=='add'){
		
		$sql="INSERT INTO {$table} 
				(compcode,debtortycode,description,depccode,depglacc, actdebccode, actdebglacc,lastuser, lastupdate,
				regfees, typegrp, updpayername, updepisode, recstatus) 
			VALUES 
				('".clr($compcode)."',
				'".clr($_POST['debtortycode'])."',
				'".clr($_POST['description'])."', 
				'".clr($_POST['depccode'])."',
				'".clr($_POST['depglacc'])."', 
				'".clr($_POST['actdebccode'])."', 
				'".clr($_POST['actdebglacc'])."', 
				'".clr($user)."', 
				NOW(),
				'".clr($_POST['regfees'])."', 
				'".clr($_POST['typegrp'])."', 
				'$user',
				'".clr($_POST['updepisode'])."',
				'$recstatus'
				)";
		
				
		
	}else if($_POST['oper']=='edit'){
		if($_POST['recstatus']=='D')	{
		$sql="UPDATE {$table} SET
				compcode= '".clr($compcode)."',
				description = '".clr($_POST['description'])."',
				depccode = '".clr($_POST['depccode'])."',
				depglacc ='".clr($_POST['depglacc'])."',
				actdebccode = '".clr($_POST['actdebccode'])."',
				actdebglacc ='".clr($_POST['actdebglacc'])."',
				lastuser = '".clr($user)."', 
				lastupdate = NOW(), 
				typegrp= '".clr($_POST['typegrp'])."', 
				recstatus = 'D'
			WHERE 
				debtortycode='{$_POST['debtortycode']}'";
		}
		
		else{
			$sql="UPDATE {$table} SET
					description = '".clr($_POST['description'])."',
					depccode = '".clr($_POST['depccode'])."',
					depglacc ='".clr($_POST['depglacc'])."',
					actdebccode = '".clr($_POST['actdebccode'])."',
					actdebglacc ='".clr($_POST['actdebglacc'])."',
					lastuser = '".clr($user)."', 
					lastupdate = NOW(),
					typegrp= '".clr($_POST['typegrp'])."',
					recstatus = '".clr($_POST['recstatus'])."'
				WHERE 
					debtortycode='{$_POST['debtortycode']}'";
			echo"$sql";
		}
				
	}else if($_POST['oper']=='del'){
		$sql="DELETE FROM {$table} WHERE debtortycode='{$_POST['id']}'";
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('debtortycode',$table,clr($_POST['debtortycode']))){
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