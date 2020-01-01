<?php
function displayExemples($phaseNum) {
	include_once("connectMySQL.php");
	$base = connectMySQL();
	$item = 1;
	$textNomPhaseEx =  "phase" . $phaseNum ."Ex";;
	if ($phaseNum == "2") $textNomPhaseCat = 'phase2Cat'; else $textNomPhaseCat = 'phase13Cat';
	
	$query = "select phrase, type1, type2 from Item where destination = '$textNomPhaseEx' order by rang";
	$result = $base->query($query);
	
	$queryCat = "select phrase from Item where destination = '$textNomPhaseCat' order by rang";
	$resultCat = $base->query($queryCat);


	$toEcho = "";
	while ($row = $result->fetch_assoc()) {
		$phrase = htmlentities($row["phrase"]);
		
		$rowCat = $resultCat->fetch_assoc();
		$phraseCat = htmlentities($rowCat["phrase"]);
		
		
		if ($item < 10) $phraseCat = " $item- $phraseCat"; else $phraseCat = "$item- $phraseCat";
		$toEcho .= "<div class='ui-grid-c saisie '>";
		
																//   ligne 1
		$toEcho .= "<div class='ui-block-a'></div>";
		$toEcho .= "<div class='ui-block-b phaseLine1'>";
		
		$toEcho .= "<div data-role='collapsible'>";   //   data-mini='true'
		$toEcho .= "<h4>$phraseCat</h4><br /><div class='media-width text-center'><strong>$phrase</strong></div><br />";
		
		if ($row["type1"]) 
			if ($row["type1"] == "photo")
				$toEcho .= "<div><img src=\"images/phase$phaseNum-Ex$item-1.png\" alt=\"Pas d'image!\" class=\"media-width center media-contener\" /></div>\n";
			else if ($row["type1"] == "video")
				$toEcho .= "<div><video src=\"images/phase$phaseNum-Ex$item-1.m4v\" preload=\"auto\" controls class=\"media-width center media-contener\">Pas de vidéo!</video></div>\n";
				
		if ($row["type2"]) 
			if ($row["type2"] == "photo")
				$toEcho .= "<br /><div><img src=\"images/phase$phaseNum-Ex$item-2.png\" alt=\"Pas d'image!\" class=\"media-width center media-contener\" /></div>\n";
			else if ($row["type2"] == "video")
				$toEcho .= "<div><video src=\"images/phase$phaseNum-Ex$item-2.m4v\" preload=\"auto\" controls class=\"media-width center media-contener\">Pas de vidéo!</video></div>\n";
				$toEcho .= "</div></div>";		// fin bloc b ligne 1
				$toEcho .= "<div class='ui-block-c'></div><div class='ui-block-d'></div>";
																//   ligne 2
				$toEcho .= "<div class='ui-block-a'></div>";
				$toEcho .= "<div class='ui-block-b phaseLine2'><textarea id='ph$phaseNum" . "_" . $item;
				$toEcho .="_rem' placeholder='Remarques...' rows='4'></textarea></div>";
				
				$toEcho .= "<div class=\"ui-block-c\" ><select id=\"phase$phaseNum" . "_score" . $item;
				$toEcho .= "_A\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
				
				$toEcho .= "<div class=\"ui-block-d\" ><select id=\"phase$phaseNum" . "_score" . $item;
				$toEcho .= "_B\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";

		
		$toEcho .= "</div><br /><br />";   // fin grid
		$item++;
	}
	$toEcho .= "<br /><br />";
	echo $toEcho;
	$result->free_result();
	$base->close();
}
?>