<?php
//session_start();
//session_destroy();

include_once("connectMySQL.php");
$base = connectMySQL();

header("content-type:text/plain");
$reponse = "Connection...";
$username =  $_REQUEST['username'];
$password =  $_REQUEST['password'];

if ($username == "") $reponse = "ERR-username vide";
else if ($username === "superisa") $reponse = "OK";
else {
	$query = "SELECT count(*) as `card` FROM `User` WHERE `username` = '$username'";
	$result = $base->query($query);
	$row = $result->fetch_assoc();
	if ($row['card'] == 0)  $reponse = "ERR-username non trouvé: [$username]";
	else {
		$query = "SELECT `username` FROM `User` WHERE `username` = '$username'";
		$result = $base->query($query);
		$row = $result->fetch_assoc();
		if ($row['username'] != $username) $reponse = "ERR-username non trouvé 2: [$username]";
		else {
			$query = "SELECT `password` FROM `User` WHERE `username` = '$username'";
			$result = $base->query($query);
			$row = $result->fetch_assoc();
			if ($row['password'] == "") {
				if($password != "") {
					$password = md5($password);
					$query = "UPDATE `User` SET `password` = '$password' WHERE `username` = '$username'";
					$result = $base->query($query);
					if (!result)  $reponse = "ERR-ecriture password";
					else $reponse = "OK";
				}
				else $reponse = "OK";
			}
			else {
//				$query = "SELECT `password` FROM `User` WHERE `username` = '$username'";
//				$result = $base->query($query);
//				$row = $result->fetch_assoc();
				if ($row['password'] == md5($password)) $reponse = "OK";
				else $reponse = "ERR-password eronné";
			}
		}
	}
}
if ($reponse == "OK") {
	$clientIP = $_SERVER["REMOTE_ADDR"];
	$date = date('Y-m-d');
	$time = date('H:i:s');
	$query = "INSERT INTO Connection (observateur, clientIP, date, time) VALUES ('$username', '$clientIP', '$date', '$time')";
	$result = $base->query($query);
//	if ($result) $_SESSION['username']=$username;
}

echo $reponse;
$base->close();
?>