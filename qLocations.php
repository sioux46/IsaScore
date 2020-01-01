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


$requete = "SELECT DISTINCT location as Etablissement FROM Score WHERE location <> '' ORDER BY $order";
$data = array("location");

$result = $base->query($requete);
echoResultTable($result, true, "Locations", $data, $colName, false);
$base->close();
?>