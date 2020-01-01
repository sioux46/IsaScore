<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();
$adresse = $_REQUEST['mail'];
$user = $_REQUEST['data'];
//if(filter_var($adresse, FILTER_VALIDATE_EMAIL)) {
	if ($user == 'superisa') $requete = "SELECT * FROM ScoreView ORDER BY id ASC";
	else $requete = "SELECT * FROM ScoreView WHERE observateur = '$user' ORDER BY id ASC";
	$result = $base->query($requete);
	$phpTab = arrayResult($result, true);
	arrayToCsvFile($phpTab, 'IsaScore.csv');
$base->close();
?>
