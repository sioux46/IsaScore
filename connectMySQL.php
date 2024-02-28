<?php
function connectMySQL() {
	//	$isa_base = new mysqli("localhost","isa","isa2014","isascoring");
	$isa_base = new mysqli("localhost","sioux","sioux","isascore");

	if ($isa_base->connect_errno) {
		echo "Connexion à la base impossible. ";
		echo $isa_base->connect_error;
		die ();
	}
	else {
		$isa_base->query("SET sql_mode = 'ONLY_FULL_GROUP_BY'");
		$isa_base->query("SET NAMES 'utf8'");
		$isa_base->query("SET GLOBAL max_allowed_packet=16*1024*1024");

		return $isa_base;
	}
}
?>
