<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	$table='chqreg';
	$table2='chqtran ';
	
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	function clr($str){
		global $mysqli;
		return $mysqli->real_escape_string($str);
	}
	
	function duplicate($code,$table,$codetext){
		global $mysqli,$compcode, $bankcode, $startno;
		$sqlDuplicate="select $code from $table where $code = '$codetext' AND compcode = '$compcode'";
		$resultDuplicate=$mysqli->query($sqlDuplicate);
		return $resultDuplicate->num_rows;
	}
	
	function duplicate2($field,$code,$code2,$table){
		global $mysqli,$compcode, $bankcode, $startno;
		$sqlDuplicate="select $code,$code2 from $table where $code = '$codetext' AND compcode = '$compcode' AND bankcode='$field'";
		$res==$mysqli->query($sqlDuplicate);
		$row=$mysqli->query($sqlDuplicate)->fetch_row();
		echo $row;
	}
	
	$mysqli->autocommit(FALSE);
	
	if($_POST['oper']=='add'){
		
		$sql="INSERT INTO {$table} 
				(compcode,bankcode,startno,endno,cheqqty,stat,adduser,adddate) 
			VALUES 
				('$compcode',
				'".clr($_POST['bankcode'])."',
				'".clr($_POST['startno'])."',
				'".clr($_POST['endno'])."',
				'".clr($_POST['endno']-$_POST['startno']+1)."',
				'ACTIVE',
				'$user',
				NOW())";
				
		$bankcode = $_REQUEST['bankcode'];
		$startno = $_REQUEST['startno'];
		$endno = $_REQUEST['endno'];
		
		$a="INSERT INTO {$table2}  
					(compcode, bankcode, cheqno, stat, lastuser) VALUES ";
		while ($startno < $endno)	{
			$a.="('$compcode', '$bankcode', '$startno', 'ACTIVE', '$user'), ";
			$startno++;
		}
		$a.="('$compcode', '$bankcode', '$startno', 'ACTIVE', '$user')";
			echo "$sql";
		 echo "$a";				
			
	}else if($_POST['oper']=='edit'){
		
/*		$sql="UPDATE {$table} SET
				startno = '".clr($_POST['startno'])."',
				endno = '".clr($_POST['endno'])."',
				cheqqty = '".clr($_POST['endno']-$_POST['startno']+1)."',
				stat = '".clr($_POST['stat'])."',
				lastuser = '$user',
				lastupdate = NOW()
			WHERE 
				sysno='{$_POST['id']}'";
				
	}else if($_POST['oper']=='del'){
/*		$sql="UPDATE {$table} SET
				deluser = '$user',
				deldate = NOW()
			WHERE 
				sysno='{$_POST['id']}'";*/
	}
	
	try{
		if (!$mysqli->query($sql)) {
			throw new Exception($mysqli->error.'</br>'.$sql);
		}
		if (!$mysqli->query($a)) {
			throw new Exception($mysqli->error.'</br>'.$a);
		}
		/*if($_POST['oper']=='add' && duplicate('bankcode',$table,clr($_POST['bankcode']))){
			throw new Exception('Duplicate key');
		}*/
		$mysqli->commit();
		
	}catch( Exception $e ){
		http_response_code(400);
		echo $e->getMessage();
		$mysqli->rollback();
	}
	$mysqli->close();
?>