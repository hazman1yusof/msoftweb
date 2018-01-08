<?php
	include_once('../../connection/connect_db.php');
	include_once('../../connection/sschecker.php');
	
	$table='material.suppgroup';
	
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
				(compcode, suppgroup, description, costcode, glaccno, adduser, adddate, advccode, advglaccno, recstatus) 
			VALUES 
				('$compcode', '".clr($_POST['suppgroup'])."', '".clr($_POST['description'])."', '".clr($_POST['costcode'])."', '".clr($_POST['glaccno'])."', '$user', NOW(), '".clr($_POST['advccode'])."', '".clr($_POST['advglaccno'])."', '".clr($_POST['recstatus'])."')";
				
		
	}else if($_POST['oper']=='edit'){
		
			if($_POST['recstatus']=='D')	{
				$sql="UPDATE {$table} SET 
						costcode = '".clr($_POST['costcode'])."', 
						glaccno = '".clr($_POST['glaccno'])."',
						advccode = '".clr($_POST['advccode'])."',
						advglaccno = '".clr($_POST['advglaccno'])."',
						recstatus = 'D',
						upduser = '$user',
						upddate = NOW(),
						deluser= '$user',
						deldate = NOW()
					WHERE 
					compcode = '$compcode' AND suppgroup='{$_POST['suppgroup']}'";
			}
			else {
				if($_POST['recstatus']=='D')	{
					$sql="UPDATE {$table} SET
					description = '".clr($_POST['description'])."',
					costcode = '".clr($_POST['costcode'])."', 
					glaccno = '".clr($_POST['glaccno'])."',
					advccode = '".clr($_POST['advccode'])."',
					advglaccno = '".clr($_POST['advglaccno'])."',
					recstatus = 'D',
					deluser = '$user',
					deldate = NOW()
				WHERE 
					compcode = '$compcode' AND suppgroup='{$_POST['suppgroup']}'";
				}else {
					$sql="UPDATE {$table} SET
					description = '".clr($_POST['description'])."',
					costcode = '".clr($_POST['costcode'])."', 
					glaccno = '".clr($_POST['glaccno'])."',
					advccode = '".clr($_POST['advccode'])."',
					advglaccno = '".clr($_POST['advglaccno'])."',
					recstatus = '".clr($_POST['recstatus'])."',
					upduser = '$user',
					upddate = NOW()
				WHERE 
					compcode = '$compcode' AND suppgroup='{$_POST['suppgroup']}'";
				}
		}
	}else if($_POST['oper']=='del'){
		 $sql="UPDATE {$table} SET  recstatus = 'D', deluser= '$user', deldate = NOW()
		 WHERE compcode = '$compcode' AND suppgroup='{$_POST['id']}'";
		
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate('suppgroup',$table,clr($_POST['suppgroup']))){
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