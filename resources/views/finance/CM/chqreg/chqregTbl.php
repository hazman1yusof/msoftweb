<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='bank';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	$responce = new stdClass();
	
	$SQL = "SELECT * FROM $table WHERE compcode='$compcode' ORDER BY bankcode ASC";
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['bankcode'];
		$responce->rows[$i]['cell']=array($row['bankcode'], $row['bankname'], $row['address1'], $row['telno']);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>