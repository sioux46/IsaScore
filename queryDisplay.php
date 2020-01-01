<?php /////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
/////////////////////    AFFICHAGE RESULT DANS <table>
//////////////////////////////////////////////////////////////////
//  $result: résultat requête
//  $colTitles: flag affichage titres colonnes
//  $item: nom de la commande (ex: UserParticipants)
//  $data: noms colonnes MySQL
// $selCol: nom colonne MySQL sélectionnée
// $toolCol: flag affichage colonne outils

function echoResultTable($result, $colTitles, $item, $data, $selCol, $toolCol) {
    if(!$result) {
    	$reponse = "ERREUR lecture base!!!";
        $reponse .= "<br />";
        $reponse .= $idcom->error;
    }
    else {
		$id = false;
		$reponse = "";
		$reponse .= "<div style='overflow:auto'><table data-ISAitem='$item' style='margin-left:auto; margin-right:auto; display:none;'>";

		if ($colTitles) {   									//   affichage thead
			$titres = $result->fetch_fields();
			$reponse .= "<thead>";

			if ($toolCol) $reponse .= "<th style='padding-left:0px; padding-right:0px; border:0px; background:#F9F9F9;'><a  title='Choisir les colonnes'  data-ISAobsId='$obsId' onclick=\"qAjaxTableChooseCols('$item');\" href='#chooseColsPanel' class='ui-btn ui-btn-inline ui-icon-eye ui-btn-icon-notext' style='margin:0px; border:0px; padding-top:10px; padding-right:20px;'></a></th>";

			$i = 0;
			foreach($titres as $colonne) {
				if ($colonne->name === 'id') {
					$id = true;
					$i++;
					continue;
				}
				if ($data[$i] == $selCol) $style = 'style="color:rgb(50, 205, 50);"';  // dodgerBlue
				else $style = "";
				$reponse .= "<th title='Trier par $colonne->name'><div $style data-ISAcolName='" . $data[$i] . "' value='" . $colonne->name . "' onclick=\" qAjaxTable('" . $item . "', true, '" . $data[$i] . "', $(this).attr('data-isa-order'));\">" . $colonne->name . "</div></th>";
				$i++;
			}

			if ($toolCol) {
				$reponse .= "<th style='display:none; border:0px; background:#F9F9F9;'></th>";
			}
			$reponse .= "</thead>";
		}
//..........................................................................................
		$reponse .= "<tbody>";									//		affichage tbody
    	while ($row = $result->fetch_array(MYSQLI_NUM)) {

			$first = true;
    		foreach($row as $donn) {
				if ($first && $id) {
					$obsId = $donn;
					$reponse .= "<tr title='Afficher/masquer la synthèse' data-ISAobsId='$donn'><td style='background-color:#F9F9F9;'>&nbsp;</td>";
					$first = false;
				}
				else if ($first) {
					$reponse .= "<tr>";
					$reponse .= "<td>$donn</td>";
					$first = false;
				}
    			else $reponse .= "<td>$donn</td>";
    		}
			if ($toolCol) {
				// pour popup menu, -->  <a href='#queryPopupMenu' data-rel='popup'data-transition='pop' ...>
				//   clickItem('$obsId', 'UserParticipants', true);
				// onclick=\" doClickActionPopup('deleteObservation', '$obsId');\"
				$reponse .= "<td style='background:#F9F9F9; padding:0px;'><div class='min-height'><a title='Agir sur la passation'  data-ISAobsId='$obsId' onclick=\"$('#chooseActionsPanel').attr({'data-isa-id':'$obsId'});displayPanelActualPar(this);\" href='#chooseActionsPanel' class='ui-btn ui-btn-inline ui-icon-action ui-btn-icon-notext' style='margin:0px; margin-left:0px; padding-top:10px; padding-right:20px; border:0px;'></a></div></td>";
			}
    		$reponse .= "</tr>\n";
    	}
    	$reponse .= "<tr style='height:40px;'></tr></tbody></table></div>";
    }
	echo $reponse;
}

//////////////////////////////////////////////////////////////
////////////////		CODAGE RESULT DANS TABLEAU PHP
/////////////////////////////////////////////////////////////
function arrayResult($result, $colTitles) {
	$nbRows = $result->num_rows;
	$nbCols=$result->field_count;
	if ($colTitles) {
		$titres = $result->fetch_fields();
		for($i = 0; $i < $nbCols; $i++) {
			$tab[0][$i] = $titres[$i]->name;
		}
		$nbRows++;
	}
	$i = ($colTitles)? 1: 0;
	for (; $i < $nbRows; $i++) {
		$row = $result->fetch_array(MYSQLI_NUM);
		for ($j = 0; $j < $nbCols; $j++) {
			$tab[$i][$j] = $row[$j];
		}
	}
	return($tab);
}
/////////////////////////////////////////////////////////////
////////////////		ECRITURE  TABLEAU DANS FICHIER CSV
/////////////////////////////////////////////////////////////
function arrayToCsvFile($tab, $fileName) {
	if ($f = @fopen($fileName, 'w')) {
		flock($f, LOCK_SH);
		for ($i = 0; $i < count($tab); $i++) {
			fputcsv($f, $tab[$i]);
		}
		flock($f, LOCK_UN);
		fclose($f);
	}
	else {
		echo "Impossible d'accéder au fichier " . $fileName . ".";
	}
}

/////////////////////////////////////////////////////////////
////////////////		ECRITURE  TEXTE DANS FICHIER TXT
/////////////////////////////////////////////////////////////
function textToTxtFile($txt, $fileName) {
	if ($f = @fopen($fileName, 'w')) {
		flock($f, LOCK_SH);

		fputs($f, $txt);

		flock($f, LOCK_UN);
		fclose($f);
	}
	else {
		echo "Impossible d'accéder au fichier " . $fileName . ".";
	}
}

//////////////////////////////////////////////////////////////
////////////////			RETOURNE MINI DETAIL
/////////////////////////////////////////////////////////////
function miniDetail($base, $data, $nbCols) {
	$textColor = "steelBlue";

	$reponse = "<tr style='display:none'><td style='background:#F7F7F7;'></td><td colspan='$nbCols'><div style='text-align:left;'><code>";

	$requete = "SELECT rem_libre FROM ScoreView WHERE id = $data";
	$result = $base->query($requete);
	$row = $result->fetch_array(MYSQLI_NUM);
	$color =  " style='color:$textColor'";
	$microColor = "; color:$textColor'";
	if ($row[0]) {
		$reponse .= "<strong>Remarques générales:</strong>";
		$reponse .= "<p><strong style='margin-left:16px$microColor'>" . $row[0] . "</strong></p>";
	}
	$reponse .= "<strong>Synthèse:</strong>";

	$requete1 = "SELECT 1_1,1_2,1_3,1_4,1_5,1_6,1_7,1_8,1_9,1_10,1_11,1_12 FROM ScoreView WHERE id = $data";
	$requete2 = "SELECT 2_1,2_2,2_3,2_4,2_5,2_6,2_7,2_8,2_9,2_10,2_11,2_12 FROM ScoreView WHERE id = $data";
	$requete3 = "SELECT 3_1,3_2,3_3,3_4,3_5,3_6,3_7,3_8,3_9,3_10,3_11,3_12 FROM ScoreView WHERE id = $data";
	$requete = 'requete';

	$multiM = true;
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
		$meanScore = ($card == 0)? '-' : round($score / $card, 1);
		if (!$card) $multiM = false;
		$card = ($card)? $card : '0';
		$score = ($score)? $score : '-';

		$reponse .= "<div>&nbsp;Ph$i:";
		$reponse .= "&nbsp;∑=&nbsp;<strong$color>$score</strong>&nbsp;(N=<span $color>$card</span>),&nbsp;&nbsp;";
		$reponse .= "x&#773;=&nbsp;<strong$color>$meanScore</strong>";
		if ($i == 1 && $multiM) $meanScore1 = $meanScore;
		if ($i == 2 && $multiM) { // x̅  x&#773;
			$meanScore2 = $meanScore;
			$meanScore12 = round((($meanScore2 + $meanScore1) / 2), 1);
			$reponse .= ",&nbsp;&nbsp;x&#773;<small><sub>(Ph1,Ph2)</sub></small>=&nbsp;<strong$color>$meanScore12</strong>";
		}
		if ($i == 3 && $multiM) {
			$meanScore123 = round((($meanScore1 + $meanScore2 + $meanScore) / 3), 1);
			$reponse .= ",&nbsp;&nbsp;x&#773;<small><sub>(Ph1,Ph2,Ph3)</sub></small>=&nbsp;<strong$color>$meanScore123</strong>";
		}
		$reponse .= "</div>";
	}
	$reponse .= "</code></div></td></tr>";
	return $reponse;
}
//////////////////////////////////////////////////////////////
////////////////					RETOURNE  DETAIL
/////////////////////////////////////////////////////////////
function detail($base, $id) {
	$textColor = "steelBlue";

	$requete = "SELECT * FROM ScoreView WHERE id = $id";

	$result = $base->query($requete);
	$row = $result->fetch_assoc();

	$color =  "<span style='color:$textColor'>";
	$miniColor =  " style='color:$textColor'";
	$microColor = "; color:$textColor'";

	$reponse = "<br /><div id='detailText' data-isa-id='$id' data-isa-participant=" . $row['participant'] . "><code  style='text-align:left;'>";

	$reponse .= "<strong>";
	$reponse .= "<strong>Observateur:</strong>&nbsp;" . $color . $row['observateur'] . "</span><br />";
	$reponse .= "<strong>Participant:</strong>&nbsp;" . $color . $row['participant'] . "</span><br />";
	$reponse .= "<strong>Période:</strong>&nbsp;" . $color . $row['period'] . "</span><br />";
	$reponse .= "<strong>Age:</strong>&nbsp;" . $color . $row['ageChrono'] . "</span><br />";
	$reponse .= "<strong>Age(dév):</strong>&nbsp;" . $color . $row['ageDev'] . "</span><br />";
	$reponse .= "<strong>Genre:</strong>&nbsp;" . $color . $row['sex'] . "</span><br />";
	$reponse .= "<strong>Date:</strong>&nbsp;" . $color . $row['date'] . "</span><br />";
	$reponse .= "<strong>Heure:</strong>&nbsp;" . $color . $row['time'] . "</span><br />";
	$reponse .= "<strong>Etablissement:</strong>&nbsp;" . $color . $row['location'] . "</span><br />";
	$reponse .= "<strong>#1:</strong>&nbsp;" . $color . $row['rem1'] . "</span><br />";
	$reponse .= "<strong>#2:</strong>&nbsp;" . $color . $row['rem2'] . "</span><br />";
	$reponse .= "<strong>#3:</strong>&nbsp;" . $color . $row['rem3'] . "</span><br />";
	$reponse .= "</strong>";

	$reponse .= miniDetail($base, $id) . "<br />";

	for ($phase = 1; $phase <= 3; $phase++) {
		$reponse .= "<strong>PHASE$phase:</strong><br />";
		for ($item = 1; $item <= 12; $item++) {
			$trueNote = false;
			if ($item < 10) $itemText = "0$item";
			else $itemText = $item;
			$index = "phase$phase" . "_score$item" . "_A";
			$note = $row[$index];
			if ($note < 0) $note = "-";
			else $trueNote = true;
			$miniReponse = "&nbsp;&nbsp;<strong>Item$itemText:</strong>&nbsp;&nbsp;&nbsp;A=<strong$miniColor>&nbsp;$note</strong>";
			$index = "phase$phase" . "_score$item" . "_B";
			$note = $row[$index];
			if ($note < 0) $note = "-";
			else $trueNote = true;
			$miniReponse .= "&nbsp;&nbsp;&nbsp;B=<strong$miniColor>&nbsp;$note</strong>";
			$index = "$phase" . "_" . "$item";
			$note = $row[$index];
			if ($note < 0) $note = "-";
			else $trueNote = true;
			$miniReponse .= "&nbsp;&nbsp;&nbsp;max(A,B)=&nbsp;<strong$miniColor>$note</strong>";
			if ($trueNote) {
				$reponse .= $miniReponse;
				$reponse .= "<br />";
			}
			$index = "ph$phase" . "_$item" . "_rem";
			if ($row[$index]) $reponse .= "&nbsp;&nbsp;&nbsp;&nbsp;<strong>Remarque:<p style='margin-left:32px;$microColor'>$row[$index]</p></strong>";
		}
		$reponse .= "<br />";
	}
	$reponse .= "<strong>id:</strong>&nbsp;" . $row['id'] . "<br />";
	$reponse .= "<strong>clientIP:</strong>&nbsp;" . $row['clientIP'] . "<br />";
	$reponse .= "<strong>Version:</strong>&nbsp;" . $row['version'] . "<br />";

	$reponse .= "</code><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>";

	return $reponse;
}
//////////////////////////////////////////////////////////////
////////////////			SEND MAIL WITH ATTACHEMENT
/////////////////////////////////////////////////////////////
function mail_attachment($filename, $destFilename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
	$header .= "Content-type:text/plain; charset=utf-8\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$destFilename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$destFilename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        echo "OK"; // or use booleans here
//		echo $header;
    } else {
        echo "mail send ... ERROR!";
    }
}
//////////////////////////////////////////////////////////////////////////////////////
?>
