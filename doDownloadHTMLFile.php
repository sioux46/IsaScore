<?php
header('Content-Type: application/octet-stream;');
header("Content-Disposition: attachment; filename=IsaScore_" . date('y.m.d-H:i:s') . ".html;");
header('Content-Length: '.filesize("IsaScore.html").';');
readfile("IsaScore.html");
?>
