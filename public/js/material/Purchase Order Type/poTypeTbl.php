<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.potype';
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
			$addSql.=" AND {$_GET['Scol']} like '%{$parts[$partsLength]}%' ";
		}
		$SQL = "SELECT * FROM $table 
			WHERE recstatus = 'A' AND compcode='$compcode'".$addSql.
			"ORDER BY potype ASC";
			
	}else{
		$SQL = "SELECT * FROM $table WHERE compcode='$compcode' AND recstatus = 'A' ORDER BY potype ASC";
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['potype'];
		$responce->rows[$i]['cell']=array($row['compcode'], 
		$row['potype'], 
		$row['typedec'], 
		$row['recstatus'], 
		$row['adddate'], 
		$row['adduser'], 
		$row['upddate'],
		$row['upduser']
		  );
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>