<?php
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();

$data = $_REQUEST['data'];
header("content-type:text/plain");

/*
$requete1 = "SELECT 1_1,1_2,1_3,1_4,1_5,1_6,1_7,1_8,1_9,1_10,1_11,1_12 FROM ScoreView WHERE id = $data";
$requete2 = "SELECT 2_1,2_2,2_3,2_4,2_5,2_6,2_7,2_8,2_9,2_10,2_11,2_12 FROM ScoreView WHERE id = $data";
$requete3 = "SELECT 3_1,3_2,3_3,3_4,3_5,3_6,3_7,3_8,3_9,3_10,3_11,3_12 FROM ScoreView WHERE id = $data";
$requete = 'requete';
for ($i = 1; $i <= 3; $i++) {
	$requete = 'requete' . $i;
	
	$result = $base->query($$requete);
	$score = 0;
	$card = 0;
	$row = $result->fetch_array(MYSQLI_NUM);
	foreach($row as $donn) {
		if ($donn != -1) {
			$score += $donn;
			$card++;
		}
	}
	$meanScore = ($card == 0)? '-' : $score / $card;
	$card = ($card)? $card : '0';
	$score = ($score)? $score : '-';
	
	$reponse .= "<div><div><strong>Phase $i</strong></div>";
	$reponse .= "Score:&nbsp;&nbsp;&nbsp;$score  ($card note";
	if ($card > 1) $reponse .= "s),&nbsp;&nbsp;&nbsp;";
	else $reponse .= "),&nbsp;&nbsp;&nbsp;";
	$reponse .= "Moyenne:   <strong>$meanScore</strong>";
	$reponse .= "</div>";
}
echo $reponse;

$base->close();  */
?>