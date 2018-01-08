<?php
	include_once('../../connection/connect_db.php');
	
	$responce = new stdClass();
	
	if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
		$SQL = "SELECT * FROM bank 
			WHERE {$_GET['Scol']} 
			LIKE '%{$_GET['Stext']}%' 
			ORDER BY bankcode DESC";
	}else{
		$SQL = "SELECT * FROM bank ORDER BY bankcode DESC";
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['bankcode'];
		$responce->rows[$i]['cell']=array($row['compcode'],$row['bankcode'],$row['bankname'],$row['address1'],$row['address2'], 
		$row['address3'], $row['postcode'], $row['statecode'], $row['country'], $row['telno'], $row['faxno'], $row['contact'], 
		$row['bankaccount'], $row['clearday'], $row['effectdate'], $row['glaccno'], $row['glccode'], $row['pctype'], $row['lastuser'], 
		$row['lastupdate'], $row['openbal']);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>