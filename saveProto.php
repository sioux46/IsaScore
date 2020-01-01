<?php
//session_start();
include_once("connectMySQL.php");
$base = connectMySQL();


//header("content-type:text/plain");

$observateur = $_REQUEST['observateur'];
$participant = $_REQUEST['participant'];

if (($observateur == 'superisa')  ||  (!$observateur) /* || (!$participant) */ )
		$reponse = "ERR-bad login";
else {
	$query = "select count(*) from `User` where `username` = '$observateur'";
	$result = $base->query($query);
	$row = $result->fetch_assoc();
	if ($row['count(*)'] == 0) $reponse = "ERR-username non trouvé: [$observateur]";
	
	else {
		$clientIP = $_SERVER["REMOTE_ADDR"];
		$reloadId = $_REQUEST['reloadId'];
		
		
		if ($reloadId == 0) {  //  nouvelle passation
			$request = "INSERT INTO Score SET clientIP = '$clientIP'";
			$result = $base->query($request);
			if ($base->errno) {
				echo $base->errno, ' ', '-' , $base->error, ' query: ', $request;
				$base->close();
				exit;
			}
				
			$reloadId = $base->insert_id;
		}
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$version = addslashes($_REQUEST['version']);
		$period = addslashes($_REQUEST['period']);
		$ageChrono = addslashes($_REQUEST['ageChrono']);
		$ageDev = addslashes($_REQUEST['ageDev']);
		$sex = addslashes($_REQUEST['sex']);
		$location = addslashes($_REQUEST['location']);
		$rem1 = addslashes($_REQUEST['rem1']);
		$rem2 = addslashes($_REQUEST['rem2']);
		$rem3 = addslashes($_REQUEST['rem3']);
		$rem_libre = addslashes($_REQUEST['rem_libre']);
		
		$query = 
			"clientIP = '$clientIP',
			date = '$date',
			time = '$time',
			version = '$version',
			observateur = '$observateur',
			participant = '$participant',
			period = '$period',
			ageChrono = '$ageChrono',
			ageDev = '$ageDev',
			sex = '$sex',
			location = '$location',
			rem1 = '$rem1',
			rem2 = '$rem2',
			rem3 = '$rem3',
			rem_libre = '$rem_libre'";
			
			$query = "UPDATE Score SET " . $query . " WHERE id = '$reloadId'";
			$result = $base->query($query);
			
		if ($base->errno) {
			echo $base->errno, ' ', 'global_info', ' ', $base->error, ' ', $query;
			$base->close();
			exit;
		}			
			
		for ($phase = 1; $phase <= 3; $phase++) {
			for ($item = 1; $item <= 12; $item++) {
				$A = 'phase' . $phase . '_score' . $item . '_A';
				$B = 'phase' . $phase . '_score' . $item . '_B';
				$request = $_REQUEST[$A];
				$query = "$A = '$request'";
				$request = $_REQUEST[$B];
				$query .= ",$B = '$request'";
				
				$rem = 'ph' . $phase . '_' . $item . '_rem';
				$request = $_REQUEST[$rem];
				
				$request = addslashes($request);
				$query .= ",$rem = '$request'";
				
				$query = "UPDATE Score SET " . $query . " WHERE id = '$reloadId';";
				$result = $base->query($query);
				
				if ($base->errno) {
					echo $base->errno, ' ', $phase, '-' , $item, ' ', $base->error, ' ', $query;
					$base->close();
					exit;
				}
			}
		}
		//............................................................
		if ($base->errno == 0) $reponse = "OK"; 
		else $reponse = $base->errno . ' '. $base->error . ' ' . $query;
	}
}
if ($_REQUEST['reloadId'] == 0) echo $reloadId;
else echo $reponse;
$base->close();
?>