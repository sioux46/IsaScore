<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();
$adresse = $_REQUEST['mail'];
$id = $_REQUEST['data'];
$detail = detail($base, $id);
$detail = '<!DOCTYPE html><html><head><meta charset="utf-8" /></head>' . $detail;
textToTxtFile($detail, 'IsaScore.html');
$destFilename = 'IsaScore_' . date('y.m.d-H:i:s') . ".html";
$base->close();
?>
