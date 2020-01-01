<?php
header('Content-Type: application/octet-stream;');
header("Content-Disposition: attachment; filename=IsaScore_" . date('y.m.d-H:i:s') . ".csv;");
header('Content-Length: '.filesize("IsaScore.csv").';');
readfile("IsaScore.csv");
?>
