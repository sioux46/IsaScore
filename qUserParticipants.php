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



if ($user == 'superisa') {
	$requete = "SELECT id, Id, observateur as Observateur, " . $requete . " FROM Score ORDER BY $order";
	$data = "id,id,observateur," . $data;
}

else {
	$requete = "SELECT id, " . $requete . " FROM Score WHERE observateur = '$user'  ORDER BY $order";
	$data = "id," . $data;
}

$data = explode(',', $data);

//echo $requete; return;


$result = $base->query($requete);
echoResultTable($result, true, "UserParticipants", $data, $colName, true);

$base->close();
?>