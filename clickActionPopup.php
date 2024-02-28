<?php
//
error_reporting(E_ALL);
ini_set("display_errors", 1);
//
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$action =  $_REQUEST['action'];
$id =  $_REQUEST['id'];
$reponse = "OK";
//----------------------------------------------- DELETE

if ($action == "deleteObservation") {
	$requete = "DELETE FROM Score WHERE id = $id";
	$result = $base->query($requete);
	echo $requete;
}
//----------------------------------------------- DETAIL

if ($action == "showFullData") $reponse = detail($base, $id);

//----------------------------------------------- DUPLICATE

if ($action == "duplicateObservation") {

	$base->query("CREATE TEMPORARY TABLE tmptable SELECT * FROM Score WHERE id = $id");
	$base->query("UPDATE tmptable SET id = NULL");
	$base->query("INSERT INTO Score SELECT * FROM tmptable");

	if ($base->affected_rows) $reponse = $base->insert_id;
	else $reponse = 0;

	$base->query("DROP TEMPORARY TABLE IF EXISTS tmptable");
}

echo $reponse;

$base->close();
?>
