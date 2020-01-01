<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$colName =  $_REQUEST['colName'];
if ($colName) $order = $colName;
else $order = 'date DESC, time DESC';

$requete = "SELECT observateur as Observateur, date as Date, time as Time, clientIP FROM Connection ORDER BY $order LIMIT 100";
$data = array("observateur", "date", "time", "clientIP");

$result = $base->query($requete);
echoResultTable($result, true,"Connections", $data, $colName, false);
$base->close();
?>