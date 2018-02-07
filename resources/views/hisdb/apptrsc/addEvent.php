<?php

// Connexion à la base de données
require_once('bdd.php');

$data	=array();
//echo $_POST['title'];
if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color']) && isset($_POST['doctor']) 
		&& isset($_POST['mrn']) && isset($_POST['telno']) && isset($_POST['telhp']) && isset($_POST['status']) && isset($_POST['case']) 
		&& isset($_POST['remarks'])){
	
	$title = $_POST['title'];
	$doctor = $_POST['doctor'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$color = $_POST['color'];
	$mrn = $_POST['mrn'];
	$telno = $_POST['telno'];
	$telhp = $_POST['telhp'];
	$status = $_POST['status'];
	$case = $_POST['case'];
	$remarks = $_POST['remarks'];

	// $sql = "INSERT INTO events(title, start, end, color, doctor) values ('$title', '$start', '$end', '$color', '$doctor')";
	$sql = "INSERT INTO apptbook(title, start, end, color, loccode, mrn, telno, telhp, status, case, remarks) values 
					('$title', '$start', '$end', '$color', '$doctor', '$mrn', '$telno', '$telhp', '$status', '$case', '$remarks')";
	//$req = $bdd->prepare($sql);
	//$req->execute();
	
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}

}

// $data['success'] = true;

// echo json_encode($data);
header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
