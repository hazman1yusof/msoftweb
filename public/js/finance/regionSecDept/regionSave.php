<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
	include_once('../../connection/connect_db.php');
	$table='sysdb.region';
	$column=getAllColumnFromTable();
	$columnid='regioncode';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	function getAllColumnFromTable(){//get all column field
		global $mysqli,$table;
		$SQL = "SHOW COLUMNS FROM $table";
		$temp=array();
		
		$result = $mysqli->query($SQL);if (!$result) { echo $mysqli->error; }
		
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			array_push($temp,$row['Field']);
		}
		return $temp;
	}
	
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
	
	function autoSyntaxAdd(array $fixColName,array $fixColValue){
		global $column,$table;
		$string='INSERT INTO '.$table.' (';
		
		for($x=0;$x<count($column);$x++){
			$string.=$column[$x];
			if($x!=(count($column)-1)){
				$string.=',';
			}
		}
		
		$string.=') VALUES (';
			
		for($x=0;$x<count($column);$x++){
			$key=array_search($column[$x], $fixColName);
			if($key>-1){
				if(!strcmp($fixColValue[$key],'NOW()')){
					$string.=clr($fixColValue[$key]);
				}else{
					$string.="'".clr($fixColValue[$key])."'";
				}
			}else if(isset($_POST[$column[$x]])){
				$string.="'".clr($_POST[$column[$x]])."'";
			}else{
				$string.="NULL";
			}
			
			if($x!=(count($column)-1)){
				$string.=', ';
			}
		}
		
		$string.=')';
		
		return $string;
		
	}
	
	function autoSyntaxUpd(array $fixColName,array $fixColValue){
		global $column,$table,$columnid;
		$string='UPDATE '.$table.' SET ';
		
		for($x=0;$x<count($column);$x++){
			$string.=$column[$x].' = ';
			
			$key=array_search($column[$x], $fixColName);
			if($key>-1){
				if(!strcmp($fixColValue[$key],'NOW()')){
					$string.=clr($fixColValue[$key]);
				}else{
					$string.="'".clr($fixColValue[$key])."'";
				}
			}else if(isset($_POST[$column[$x]])){
				$string.="'".clr($_POST[$column[$x]])."'";
			}else{
				$string.="NULL";
			}
			
			if($x!=(count($column)-1)){
				$string.=',';
			}
		}
		$string.=" WHERE ".$columnid." = '".$_POST[$columnid]."'";
		
		return $string;
	}
	
	
	$mysqli->autocommit(FALSE);
	
	if($_POST['oper']=='add'){
	
		$sql=autoSyntaxAdd(['compcode','adduser','adddate','recstatus'],[$compcode,$user,'NOW()','A']);
		
	}else if($_POST['oper']=='edit'){
		
		$sql=autoSyntaxUpd(['compcode','upduser','upddate'],[$compcode,$user,'NOW()']);
				
	}else if($_POST['oper']=='del'){
	
		$sql=autoSyntaxUpd(['compcode','deluser','deldate','recstatus'],[$compcode,$user,'NOW()','D']);
	}
	
	try{
		
		if($_POST['oper']=='add' && duplicate($columnid,$table,clr($_POST[$columnid]))){
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