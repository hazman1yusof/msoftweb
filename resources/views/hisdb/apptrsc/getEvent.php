<?php   

require_once('bdd.php');

$drrsc = $_POST['drrsc'];

// echo $drrsc;

$sql = "SELECT id, title, start, end, color FROM events WHERE doctor='$drrsc'";

// echo $sql;

$req = $bdd->prepare($sql);
$req->execute();

$events = $req->fetchAll();

echo json_encode($events);

exit;


?>