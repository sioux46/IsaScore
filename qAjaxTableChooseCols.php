<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$colName =  $_REQUEST['colName'];
if ($colName) $order = $colName;
else $order = 'date DESC, time DESC';

$user = $_REQUEST['user'];

if ($user == 'superisa') {
	$requete = "SELECT `id`, `observateur` as `Observateur`, `participant` as `Participant`, `rem1` as `#1`, `rem2` as `#2`,`rem3` as `#3`,`location` as `Etablissement`, `date` as `Date`, `time` as `Heure`  FROM `Score`  ORDER BY $order"; //  LIMIT 500
	$data = array("id" ,"observateur", "participant", "rem1", "rem2", "rem3", "location", "date", "time");
}


else {
	$requete = "SELECT `id`, `participant` as `Participant`, `rem1` as `#1`, `rem2` as `#2`,`rem3` as `#3`,`location` as `Etablissement`, `date` as `Date`, `time` as `Heure`  FROM `Score` WHERE ((`participant` <> '') AND (`observateur` = '$user'))  ORDER BY $order";
	$data = array("id" , "participant", "rem1", "rem2", "rem3", "location", "date", "time");
}

$result = $base->query($requete);
echoResultTable($result, true, "UserParticipants", $data, $colName, true);

$base->close();
?>