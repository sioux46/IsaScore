<?php
//session_start();                      INUTILISÉ
include_once("connectMySQL.php");
$base = connectMySQL();

$observateur = $_REQUEST['observateur'];
//$participant = $_REQUEST['participant'];

$queryBack = "UPDATE Score SET participant = (SELECT CONCAT('!', participant)) WHERE observateur = '$observateur' AND participant LIKE '???%???'";
$result = $base->query($queryBack);
if (!$result) $reponse = "ERR-bad rename participant";	
else $reponse = "OK";

$base->close();
echo $reponse;
?>