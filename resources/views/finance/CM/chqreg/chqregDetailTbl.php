<?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='chqreg';
	$table2='bank';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	$responce = new stdClass();
	
	$bc=$_GET['bankcode'];
	
	$SQL = "SELECT  c.compcode, c.bankcode, c.startno, c.endno, c.cheqqty, c.stat, c.adduser, c.adddate,
		c.lastuser, c.lastupdate, c.upduser, c.upddate FROM $table c, $table2 b WHERE c.bankcode=b.bankcode 
		AND b.compcode=c.compcode AND b.bankcode='$bc' 
		AND c.compcode='$compcode' ORDER BY c.startno DESC";
		//ORDER BY sysno DESC c.sysno,
		
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
	
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=array($row['compcode'], $row['bankcode'], $row['startno'], $row['endno']);
		$responce->rows[$i]['cell']=array($row['compcode'], $row['bankcode'], $row['startno'], $row['endno'], $row['cheqqty'], $row['stat'], $row['adduser'], $row['adddate'], $row['lastuser'], $row['lastupdate'], $row['upduser'], $row['upddate']);
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>