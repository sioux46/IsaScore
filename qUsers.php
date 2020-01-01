<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$asc_desc = $_REQUEST['order'];
$colName =  $_REQUEST['colName'];
if ($colName) $order = $colName . " " . $asc_desc;
else $order = 'date DESC, time DESC';

$user = $_REQUEST['user'];
$requete = $_REQUEST['requete'];
$data = $_REQUEST['data'];


if ($user == "superisa") {  
	$requete = "SELECT s1.observateur as Observateur, s1.clientIP, s1.date as Date, s1.time as Time FROM Connection s1 LEFT JOIN Connection s2 ON s1.observateur = s2.observateur AND s1.id < s2.id WHERE s2.id IS NULL ORDER BY $order";
	$data = array("observateur", "clientIP", "date", "time");
}
else {
	$requete = "SELECT DISTINCT username as Observateur FROM User ORDER BY username" . " " . $asc_desc;
	$data = array("observateur");
}

$result = $base->query($requete);
echoResultTable($result, true, "Users", $data, $colName, false);
$base->close();
?>