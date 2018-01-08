<?php
    include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.uom';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	$responce = new stdClass();
	
	if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
			
		$addSql='';
		$searchcol=$_GET['Scol'];
		$searchStext=$_GET['Stext'];
		
		$parts = explode(' ', $searchStext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.=" AND {$_GET['Scol']} like '%{$parts[$partsLength]}%'";
		}
		$SQL = "SELECT * FROM $table 
				WHERE compcode='$compcode'".$addSql.
				"ORDER BY uomcode ASC";

	}else{
		$SQL = "SELECT * FROM $table WHERE compcode='$compcode' ORDER BY uomcode ASC";
		
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['uomcode'];
		$responce->rows[$i]['cell']=array($row['compcode'],
		$row['uomcode'],
		$row['description'],
		$row['convfactor'],
		$row['adduser'], 
		$row['adddate'],
		$row['upduser'],
		$row['upddate'], 
		$row['deluser'],
		$row['deldate'],
		$row['recstatus']);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>