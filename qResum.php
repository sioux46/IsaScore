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

$requete = "SELECT observateur as Observateur, participant as Participant, location as Etablissement, date as Date, time as Heure FROM Score WHERE observateur NOT IN ('Seb', 'Christine') ORDER BY $order LIMIT 200";
$data = array("observateur", "participant", "location", "date", "time");

$result = $base->query($requete);
echoResultTable($result, true,"Resum", $data, $colName, false);
$base->close();
?>