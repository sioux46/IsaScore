<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$data = $_REQUEST['data'];
$nbCols = $_REQUEST['nbCols'];
header("content-type:text/plain");

//preg_match('/^(\d+)/', $data, $idTab);
//$id = $idTab[1];

$reponse = miniDetail($base, $data, $nbCols);
echo $reponse;

$base->close();
?>