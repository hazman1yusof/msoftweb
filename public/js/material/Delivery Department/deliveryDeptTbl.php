<?php
    include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.deldept';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	$responce = new stdClass();
	
	
	if(isset($_GET['Scol']) && $_GET['Stext'] == 'deptcode'){
		$SQL = "SELECT * FROM $table 
			WHERE {$_GET['Scol']} 
			LIKE '%{$_GET['Stext']}%' 
			ORDER BY deptcode ASC";
			
			}
	
	else if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
		
		$addSql='';
		$searchcol=$_GET['Scol'];
		$searchStext=$_GET['Stext'];
		
		$parts = explode(' ', $searchStext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.="AND {$_GET['Scol']} like '%{$parts[$partsLength]}%' ";
		}
		
		$SQL = "SELECT * FROM $table 
			WHERE compcode='$compcode'".$addSql. 
			"ORDER BY deptcode ASC";
			}
	
	else{
	
		$SQL = "SELECT * FROM $table ORDER BY deptcode ASC";
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['deptcode'];
		$responce->rows[$i]['cell']=array($row['compcode'],
		$row['deptcode'],
		$row['description'],
		$row['addr1'],
		$row['addr2'], 
		$row['addr3'],
		$row['addr4'], 
		$row['tel'], 
		$row['fax'],
		$row['generaltel'],
		$row['generalfax'],
		$row['contactper'],
		$row['recstatus'], 
		$row['adduser'], 
		$row['adddate'],
		$row['upduser'], 
		$row['upddate']);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>