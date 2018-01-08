<?php
	include_once('../../connection/connect_db.php');
	
	$responce = new stdClass();
	$table='sysdb.sector';
	$column=['compcode','sectorcode','regioncode','description','adddate','adduser','upddate','upduser','lastinvno','mrnsrc','mrntrantype'];
	$columnid='sectorcode';
	
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
	
	function cellArray($row){
		global $column;
		$temp=array();
		for($x=0;$x<count($column);$x++){
			array_push($temp,$row[$column[$x]]);
		}
		return $temp;
	}
	
	if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
		$SQL = "SELECT * FROM $table 
			WHERE {$_GET['Scol']} 
			LIKE '%{$_GET['Stext']}%' 
			AND recstatus = 'A' 
			ORDER BY $columnid DESC";
	}else{
		$SQL = "SELECT * FROM $table WHERE recstatus = 'A' AND regioncode = '".$_GET['regioncode']."' ORDER BY $columnid DESC";
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row[$columnid];
		$responce->rows[$i]['cell']=cellArray($row);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>