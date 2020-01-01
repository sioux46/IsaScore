<?php
//session_start();
include_once("connectMySQL.php");
$base = connectMySQL();

//header("content-type:text/plain");

$observateur = $_REQUEST['observateur'];
$participant = $_REQUEST['participant'];

if (($observateur == 'superisa') ||  (!$observateur) || (!$participant))
	$reponse = "ERR-bad login";
else {
	$pendingParticipant = "???" . $participant . "???";
	$queryExist = "SELECT id FROM Score WHERE participant = '$pendingParticipant' AND observateur = '$observateur'";
	$result = $base->query($queryExist);
	if ($result->num_rows) {
		$row = $result->fetch_assoc();
		$reponse = json_encode(array("REPRISE" => $row['id']));
	}
	else $reponse = json_encode(array("REPRISE" => false));
}
	
$base->close();
echo $reponse;
?>