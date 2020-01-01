<?php
//session_start();
include_once("connectMySQL.php");
$base = connectMySQL();

$id = $_REQUEST['id'];

$query = "SELECT * FROM Score WHERE id = '$id'";
$result = $base->query($query);
if (!$result) $reponse = "ERR-bad participant id";	
else {
	$reponse = $result->fetch_assoc();
	
	$array = array();
	$match = preg_match("/^!?\?\?\?(.+)\?\?\?$/", $reponse['participant'], $array);
	$participant = $array[1];
	if ($participant) $reponse['participant'] = $participant;
	
//	$participant = $reponse['participant'];
//	$query = "UPDATE Score SET participant = (SELECT CONCAT('???', CONCAT('$participant', '???'))) WHERE id = '$id'";
//	$result = $base->query($query);
}
$base->close();
echo json_encode($reponse);
?>