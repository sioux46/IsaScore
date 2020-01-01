<?php
//session_start();
include_once("connectMySQL.php");
$base = connectMySQL();

//header("content-type:text/plain");

$observateur = $_REQUEST['observateur'];
$participant = $_REQUEST['participant'];

if (($observateur == 'superisa') ||  (!$observateur) || (!$participant))
		$reponse = "ERR-bad login";
else {
	$query = "select count(*) from `User` where `username` = '$observateur'";
	$result = $base->query($query);
	$row = $result->fetch_assoc();
	if ($row['count(*)'] == 0) $reponse = "ERR-username non trouvé: [$observateur]";
	else {
		$clientIP = $_SERVER["REMOTE_ADDR"];
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$version = $_REQUEST['version'];
		$location = $_REQUEST['location'];
		$rem1 = $_REQUEST['rem1'];
		$rem2 = $_REQUEST['rem2'];
		$rem3 = $_REQUEST['rem3'];
		$rem_libre = $_REQUEST['rem_libre'];
		
		$query = 
			"clientIP = '$clientIP',
			date = '$date',
			time = '$time',
			version = '$version',
			observateur = '$observateur',
			participant = '$participant',
			location = '$location',
			rem1 = '$rem1',
			rem2 = '$rem2',
			rem3 = '$rem3',
			rem_libre = '$rem_libre'";
		for ($phase = 1; $phase <= 3; $phase++) {
			for ($item = 1; $item <= 12; $item++) {
				$A = 'phase' . $phase . '_score' . $item . '_A';
				$B = 'phase' . $phase . '_score' . $item . '_B';
				$request = $_REQUEST[$A];
				$query .= ",$A = '$request'";
				$request = $_REQUEST[$B];
				$query .= ",$B = '$request'";
				
				$rem = 'ph' . $phase . '_' . $item . '_rem';
				$request = $_REQUEST[$rem];
				$query .= ",$rem = '$request'";
			}
		}

/*		
//  gestion backgroundSave
		if (preg_match("/^\?\?\?.+\?\?\?$/", $participant)) $participantToDel = $participant;
		else $participantToDel = '???' . $participant . '???';
		
		// supprimer ???$participant??? si existe avant de stoquer nouveau
		$queryBack = "DELETE FROM Score WHERE participant = '$participantToDel' AND observateur = '$observateur'";
		$base->query($queryBack);
//  fin gestion backgroundSave
*/
		if (preg_match("/^\?\?\?.+\?\?\?$/", $participant)) {  // demande de backSave
			$queryExist = "SELECT COUNT(*) AS card FROM Score WHERE participant = '$participant' AND observateur = '$observateur'";
			$result = $base->query($queryExist);
			$row = $result->fetch_assoc();
			if ($row['card'] == 0) {
				$queryInsert = "INSERT INTO Score SET " . $query;
				$result = $base->query($queryInsert);
			}
			else {
				$queryUpdate = "UPDATE Score SET " . $query . " WHERE participant = '$participant' AND observateur = '$observateur'";
				$result = $base->query($queryUpdate);
			}
/*		
			$array = array();
			$match = preg_match("/^\?\?\?(.+)\?\?\?$/", $participant, $array);
			$shortName = $array[1];
			
			$query = "UPDATE Score SET " . $query . " WHERE participant = '$shortName' AND observateur = '$observateur'";
			$result = $base->query($query);
*/			
			
		}
		else {													// save explicite (bouton)
			$participantBackSave = '???' . $participant . '???';
			$queryExplicite = "UPDATE Score SET " . $query . " WHERE participant = '$participantBackSave' AND observateur = '$observateur'";
			$base->query($queryExplicite);
			if ($base->affected_rows == 0) {
				$queryExplicite2 = "UPDATE Score SET " . $query . " WHERE participant = '$participant' AND observateur = '$observateur'";
				$base->query($queryExplicite2);
			}
		}

		
		if ($base->affected_rows == 0) $reponse = "ERR-bad save";	
		else $reponse = "OK";
	}
}
$base->close();
echo $reponse;
//echo $queryExplicite2;
?>


