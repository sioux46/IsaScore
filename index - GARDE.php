<!DOCTYPE html> 
<html>
<head> 
	<title>IsaScore</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1.0, user-scalable=0" />
	
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />

<!--	<link rel="stylesheet" href="themes/isaTheme.min.css" />-->
	<link rel="stylesheet" href="lib/jquery.mobile-1.4.0.min.css" />
<!--	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile.structure-1.4.0.min.css" />-->
<!--	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />-->
<!--	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">-->

	
	<script src="lib/jquery-2.0.3.min.js"></script>
<!--	<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>-->
<!--	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
	<script src="scorescale.js"></script>
	
	<script src="lib/jquery.mobile-1.4.0.min.js"></script>
<!--	<script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>-->
	
<!--																		S T Y L E    -->
	<link rel="stylesheet" href="isa.css">
	
</head>
<body>
<?php																	//	P H P
include_once("displayExemples.php");																			
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();
?>

<!--********************************************************************************************************-->
<!--*************************************************************************************** A C C U E I L ---->
<!--********************************************************************************************************-->
<div data-role="page" id="accueil">
<!--.............................................-->

<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
	<strong><p></p></strong>
	<div>
		<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
		<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
	</div>
</div>
<!--.............................................-->

    <div role="main" class="ui-content text-center">
		
<!--		<div class="resumPass"></div>-->
		<br />
		<h1>Echelle d'imitation</h1>
		Version 2014
		<br /><br />
		<div><img src="images/intro.jpg" alt="image" width="250" /></div>
			<h4>Jacqueline Nadel</h4>
		CNRS USR3246<br />
		H&ocirc;pital de La Salp&ecirc;tri&egrave;re, Paris<br />
		e-mail:jacqueline.nadel@upmc.fr
		
		<br /><br />
		<img src="images/logoSebISA.png" alt="Pas d'image" width="220" /><br />
		<img src="images/iconClaire.png" alt="Pas d'icon" style="cursor:pointer" title="Vérifier les mises à jour d'IsaScore" onclick="updateIsa();" />
		<br /><code></code><br /><br /><br /><br /><br /><br />
    </div>
<!--.............................................      F O O T E R-->

    <div data-role="footer" data-position="fixed" data-fullscreen="true">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#record" data-transition="slide" data-direction="reverse"  data-icon="arrow-l">Sauvegarde</a></li>
				<li><a href="#listeObjs" data-transition="slideup" data-icon="arrow-d">Passation</a></li>
				<li><a href="#query" data-transition="slide" data-icon="arrow-r">Requêtes</a></li>
			</ul>
		</div>					
    </div>   
</div>

<!--********************************************************************************************************-->
<!--******************************************************************************* Q U E R Y ---->
<!--********************************************************************************************************-->
<div data-role="page" id="query">

<div data-role="popup" class="alertPopup ui-content"><strong><p></p></strong></div>


<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
	<strong><p></p></strong>
	<div>
		<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
		<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
	</div>
</div>


<!--...................  pop up ACTION sur passation ......... Hors Service .................-->
<div data-role="popup" id="queryPopupMenu">
    <ul data-role="listview" data-inset="true" style="min-width:210px;">
		<li data-role="list-divider">Passation</li>
        <li><a href="#" id="detailAncor" data-transition="slide" class="ui-btn" onclick="doClickActionPopup('showFullData');">Tout afficher</a></li>
        <li><a href="#" data-rel="back" class="ui-btn" onclick="doClickActionPopup('deleteObservation');">Supprimer la passation</a></li>
    </ul>
</div>
<!--.............................................-->
<!--.............................................-->
<div role="main" class="ui-content">
		<br />
		<h1>Requêtes</h1>
		<div class="loader" style="display:none">
			<img src="images/loaderB32.gif">
		</div>
		
		
<!--		***************************************        	-->
<!--						GABARITS        				 -->
<!--		 ******************************************       -->

<div class="qrTable text-center">
	<!--.............................................-->
	<div id="qrUserParticipants" style="display:none">
		<div>
			<a data-role='button'  href='#' id='qUserParticipants' data-inline='true' style="color:dodgerBlue;"></a>
		</div>
		<div id='cUserParticipants'></div>
		<div id='rUserParticipants'"></div>       
	</div>
	<!--.............................................-->
	<div>
		<a data-role='button'  href='#' id='qResum' title='Afficher les 25 dernières passations' data-inline='true'>Les 25 dernières passations</a>
	</div>
	<div id="rResum"></div>   
	<!--.............................................-->
	<div>
		<a data-role='button'  href='#' id='qUsers' title='Afficher la liste des observateurs' data-inline='true'>Les observateurs</a>
	</div>
	<div id="rUsers"></div>   
	<!--.............................................-->
	<div>
		<a data-role='button'  href='#' id='qLocations' title='Afficher la liste des établissements' data-inline='true'>les établissements</a>
	</div>
	<div id="rLocations"></div>   
	<!--.............................................-->
</div>


<br /><br /><br /><br />
</div>

<!--.............................................      F O O T E R-->

    <div data-role="footer" data-position="fixed" data-fullscreen="false">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slide" data-direction="reverse" data-icon="arrow-l">Accueil</a></li>
			</ul>
		</div>					
    </div>   
		
</div>
<!--.............................................  					GABARI DIALOG DETAILS  -->
<!--.............................................-->

<div data-role="page" id="detailDialog">
		<br /><h3 style="text-align:center">Détail de la passation</h3>
	<div role="main" class="ui-content">
		<div style='text-align:left;'><code>
		</code></div>
	</div>
<!--.........................-->
    <div data-role="footer" data-position="fixed" data-fullscreen="false">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#query" data-transition="slide" data-direction="reverse" data-icon="arrow-l">Requêtes</a></li>
				<li><a href="#accueil" data-transition="slide" data-direction="reverse" data-icon="home">Accueil</a></li>
			</ul>
		</div>					
    </div>   
</div>
<!--.............................................-->

<!--********************************************************************************************************-->
<!--**************************************************************************************  R E C O R D  ----->
<!--********************************************************************************************************-->
<div data-role="page" id="record">
	
	<div data-role="popup" class="alertPopup ui-content"><strong><p></p></strong></div>
	
	<div class="loader" style="display:none"><img src="images/loaderB32.gif"></div>
	

	<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
		<strong><p></p></strong>
		<div>
			<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
			<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
		</div>
	</div>

    <div role="main" class="ui-content">	
	<br />
	<h1>Sauvegarde</h1>
	
	<div class="padRecord">
		<div id="popupLogin" class="login" style="padding:10px 20px;">
			<h3 class="text-center">Identifiez-vous</h3>
		    <input type="text" name="user" id="observateur" value="" placeholder="Observateur">

		    <input type="password" name="pass" id="password" value="" placeholder="Mot de passe">
			<br />
			<a data-role="button"  href="#" id="login">Connection</a>
		</div>
<!--		        -->
		<div id="sauvegarde">
			<span></span>
			<div class="ui-field-contain">
				<label for="participant">Participant:</label>
				<input type="text" name="user" id="participant">
			</div>
			<div class="ui-field-contain">
				<label for="location">Institution:</label>
				<input type="text" name="pass" id="location">
			</div>
			<div class="ui-field-contain">
				<label for="rem1">#1:</label>
				<input type="text" name="rem1" id="rem1">
			</div>
			<div class="ui-field-contain">
				<label for="rem2">#2:</label>
				<input type="text" name="rem2" id="rem2">
			</div>
			<div class="ui-field-contain">
				<label for="rem3">#3:</label>
				<input type="text" name="rem3" id="rem3">
			</div>
			<div class="ui-field-contain">
				<label for="rem_libre">Remarques:</label>
				<textarea name="rem_libre" id="rem_libre"></textarea>
			</div>
			<br />
			<a data-role="button" href="#" style="border-bottom:1px solid #F88;" id="saveProto">Enregistrer la passation</a>
		</div>
	</div>
	</div>
	<br /><br /><br /><br />
<!--.............................................      F O O T E R-->

    <div data-role="footer" data-position="fixed" data-fullscreen="true">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slide" data-icon="arrow-r">Accueil</a></li>
			</ul>
		</div>					
    </div>   
</div>

<!--********************************************************************************************************-->
<!--**************************************************************************************** listeObjets ----->
<!--********************************************************************************************************-->
<div data-role="page" id="listeObjs">
<!--.........................................      C O N T E N T -->

	<div role="main" class="ui-content">
		<br />
		<div class="resumPass"></div>	
	
		<h2>Liste des objets en double exemplaire</h2>
		
        <ul data-role="listview" data-inset="true">
		
<?php 																						//	P H P
$query = "select phrase from Item where destination = 'listeObjs' order by rang";
$result = $base->query($query);
$item = 1;
while ($row = $result->fetch_assoc()) {
	$phrase = htmlentities($row["phrase"]);
	echo "<li><a href='#objet$item'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$phrase</a>";
	$item++;
}
?>
        </ul>
	</div>
<!--....................................................      F O O T E R-->

	<div data-role="footer">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slidedown" data-icon="arrow-u">Accueil</a></li>
<!--				<li><a>&nbsp;</a></li>-->
				<li><a href="#consigne" data-transition="slideup" data-icon="arrow-d">Consigne</a></li>
			</ul>
		</div>		
	</div>
</div>
<!--------------------------------------------------------------------------------DIALOGS  Objets ---->
<!---------------------------------------------------------------------------------------------------->
<?php 																						//	P H P
$item = 1;
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
	$phrase = htmlentities($row["phrase"]);
	echo "<!-- Objet $item ------------------------------------------------------------>\n";
	echo "<div data-role='page' data-dialog='true' id=\"objet$item\"><div data-role='header'><h2>Exemple</h2></div>";
	
	echo "<div role=\"main\" class=\"ui-content\"><p class=\"text-center\"><strong>$phrase</strong></p><div><img src=\"images/objet$item.png\" alt=\"Pas d'image!\" width=100% /></div></div></div>";
	$item++;
}
$result->free_result();
?>



<!--********************************************************************************************************-->
<!--******************************************************************************************** CONSIGNE ---->
<!--********************************************************************************************************-->
<div data-role="page" id="consigne">
<!--....................................................       -->

    <div role="main" class="ui-content">
		<br />
		<div class="resumPass"></div>	
		<h2>Consigne de passation</h2>
<h4>L'outil d'évaluation est composé de 3 sous échelles comprenant chacune 12 items</h4><br />
11-	<i>Imitation spontanée,</i><br /> 
2-	<i>Reconnaissance d'être imité</i><br /> 
3-	<i>Imitation sur requête en utilisant la consigne « fais comme moi »</i><br /><br />

La sous échelle 3 doit impérativement être passée en dernier (sinon on ne sait plus ce qui est spontané). Par contre si l'imitation spontanée ne se déclenche pas, on peut débloquer la situation en passant à la sous échelle 2 puis revenir ensuite à la 1.<br /><br />


L'ordre <u>à l'intérieur de chaque sous échelle</u> est plus flexible. On peut avoir oublié un item et y revenir ensuite, ou préférer un item parce que l'enfant est plus familier de l'action proposée. Toutefois celui que nous indiquons est ludique (ce qui est très important) et largement testé. Il  permet d'éviter une succession de gestes non significatifs, ce qui n'est pas conseillé.<br /><br />

Des cartons illustrant chaque action peuvent êtres utilisés comme support de rappel pour l'examinateur (exemple : Faire un jeu de 36 cartes, ou faire un poster des actions de chaque sous échelle).<br /><br /> 

Deux actions sont proposées pour certains items au choix de l'examinateur.<br />
	</div>
<!--...............................................      F O O T E R  -->	
    <div data-role="footer">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#listeObjs"data-transition="slidedown" data-icon="arrow-u">Objets</a></li>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>    
				<li><a href="#phase1" data-transition="slideup" data-icon="arrow-d">Phase 1</a></li>
			</ul>
		</div>
    </div>
</div>

<!--********************************************************************************************************-->
<!--************************************************************ IMITATION SPONTANÉE  ************ PHASE 1---->
<!--********************************************************************************************************-->
<div data-role="page" id="phase1" data-dom-cache="false">
<!--.........................................      C O N T E N T -->			
<div role="main" class="ui-content">

		<br />
		<div class="resumPass"></div>	
		<h1>Phase 1</h1>
		<h2 class="scoring">Imitation Spontanée</h2>
		<br />
		<h4>Consignes de cotation:</h4>

<p>0 = Aucun intérêt manifesté pour l'objet</p>
<p>1 = Emergence : intérêt manifesté pour l'objet (regard, mouvement ou  amorce de mouvement vers l'objet)</p>
<p>2 = Imitation partielle (imitation impliquant une autre partie du corps, imitation sur un autre objet, mouvement non imitatif de la partie du corps concerné, tentative non aboutie), imitation d'une partie de la séquence seulement</p>
<p>3 = Imitation réussie</p>
<br />

<!-------------------------------->		

<?php																				//	P H P
$query = "select phrase from Item where destination = 'phase13Cat' order by rang";
$result = $base->query($query);
$item = 1;
$echo = "";
while ($row = $result->fetch_object()) {
	$phrase = htmlentities($row->phrase);
	if ($item < 10) $phrase = " $item- $phrase"; else $phrase = "$item- $phrase";
	$echo .= "<div class='ui-grid-c saisie'>";
	$echo .= "<div class=\"ui-block-a\"><a data-role=\"button\" href=\"#phase1-Ex$item\"></a></div>\r";
	$echo .= "<div class=\"ui-block-b\"><p>$phrase</p></div>\r";
	$echo .= "<div class=\"ui-block-c\" ><select id=\"phase1_score$item";
	$echo .= "_A\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	$echo .= "<div class=\"ui-block-d\" ><select id=\"phase1_score$item";
	$echo .= "_B\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	
	$echo .= "<div class='ui-block-a'></div>";
	$echo .= "<div class='ui-block-b'><textarea id='ph1_$item";
	$echo .="_rem' placeholder='Remarques...'></textarea></div>";
	$echo .= "<div class='ui-block-c'></div><div class='ui-block-d'></div></div><br />";
	$echo .= "<!------->";
	$item++;
}
echo $echo;
$result->free_result();
?>



<!------->
			
</div>
		
<!--..........................................      F O O T E R -->
    <div data-role="footer">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#consigne"data-transition="slidedown" data-icon="arrow-u">Consigne</a></li>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>    
				<li><a href="#phase2" data-transition="slideup" data-icon="arrow-d">Phase 2</a></li>
			</ul>
		</div>
    </div>
</div>
<!--********************************************************************************************************-->
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------- DIALOGS ----- Phase 1 Ex  --->
<!------------------------------------------------------------------------------------------------------------>
<?php 																						//	P H P
displayExemples($base, "1");
?>


<!--********************************************************************************************************-->
<!--********************************************************************************** PHASE 2 ---->
<!--********************************************************************************************************-->

<div data-role="page" id="phase2" data-dom-cache="false">
<!--.....................................................      C O N T E N T -->			
<div role="main" class="ui-content">
	
		<br />
		<div class="resumPass"></div>	
		<h1>Phase 2</h1>
		<h2 class="scoring">Reconnaissance d'être imité</h2>
		<br />
		<h4>Consignes de cotation:</h4>

<p>0 = Aucun intérêt manifesté pour l'objet</p>
<p>1 = Emergence : regarde, sourit,  vocalise, se rapproche ...</p>
<p>2 = Reconnaissance explicite</p>
<p>3 = Reconnaissance communicative</p>

<br />
<h4>L'expérimentateur imite...</h4>

<!-------------------------------->		

<?php																				//	P H P
$query = "select phrase from Item where destination = 'phase2Cat' order by rang";
$result = $base->query($query);
$item = 1;
$echo = "";
while ($row = $result->fetch_object()) {
	$phrase = htmlentities($row->phrase);
	if ($item < 10) $phrase = " $item- $phrase"; else $phrase = "$item- $phrase";
	$echo .= "<div class='ui-grid-c saisie'>";
	$echo .= "<div class=\"ui-block-a\"><a data-role=\"button\" href=\"#phase2-Ex$item\"></a></div>\r";
	$echo .= "<div class=\"ui-block-b\"><p>$phrase</p></div>\r";
	$echo .= "<div class=\"ui-block-c\" ><select id=\"phase2_score$item";
	$echo .= "_A\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	$echo .= "<div class=\"ui-block-d\" ><select id=\"phase2_score$item";
	$echo .= "_B\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	
	$echo .= "<div class='ui-block-a'></div>";
	$echo .= "<div class='ui-block-b'><textarea id='ph2_$item";
	$echo .="_rem' placeholder='Remarques...'></textarea></div>";
	$echo .= "<div class='ui-block-c'></div><div class='ui-block-d'></div></div><br />";
	$echo .= "<!------->";
	$item++;
}
echo $echo;
$result->free_result();
?>



				
</div>
		
<!--..........................................      F O O T E R -->
    <div data-role="footer">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#phase1"data-transition="slidedown" data-icon="arrow-u">Phase 1</a></li>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>    
				<li><a href="#phase3" data-transition="slideup" data-icon="arrow-d">Phase 3</a></li>
			</ul>
		</div>
    </div>
</div>
<!--********************************************************************************************************-->
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------- DIALOGS ----- Phase 2 Ex  --->
<!------------------------------------------------------------------------------------------------------------>
<?php 																						//	P H P
displayExemples($base, "2");
?>


<!--********************************************************************************************************-->
<!--********************************************************************************** PHASE 3 ---->
<!--********************************************************************************************************-->

<div data-role="page" id="phase3" data-dom-cache="false">
<!--.....................................................      C O N T E N T -->			
<div role="main" class="ui-content">
	
		<br />
		<div class="resumPass"></div>	
		<h1>Phase 3</h1>
		<h2 class="scoring">Imitation sur demande</h2>
		<br />
		<h4>Consignes de cotation:</h4>

<p>0 = Aucun intérêt manifesté pour l'objet</p>
<p>1 = Emergence : intérêt manifesté pour l'objet (regard, mouvement ou  amorce de mouvement vers l'objet)</p>
<p>2 = Imitation partielle (imitation impliquant une autre partie du corps, imitation sur un autre objet, mouvement non imitatif de la partie du corps concerné, tentative non aboutie), imitation d'une partie de la séquence seulement</p>
<p>3 = Imitation réussie</p>
<br />

<!-------------------------------->		

<?php																					//	P H P
$query = "select phrase from Item where destination = 'phase13Cat' order by rang";
$result = $base->query($query);
$item = 1;
$echo = "";
while ($row = $result->fetch_object()) {
	$phrase = htmlentities($row->phrase);
	if ($item < 10) $phrase = " $item- $phrase"; else $phrase = "$item- $phrase";
	$echo .= "<div class='ui-grid-c saisie'>";
	$echo .= "<div class=\"ui-block-a\"><a data-role=\"button\" href=\"#phase3-Ex$item\"></a></div>\r";
	$echo .= "<div class=\"ui-block-b\"><p>$phrase</p></div>\r";
	$echo .= "<div class=\"ui-block-c\" ><select id=\"phase3_score$item";
	$echo .= "_A\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	$echo .= "<div class=\"ui-block-d\" ><select id=\"phase3_score$item";
	$echo .= "_B\"><option value=-1> - </option><option value=0> 0 </option><option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option></select></div>";
	
	$echo .= "<div class='ui-block-a'></div>";
	$echo .= "<div class='ui-block-b'><textarea id='ph3_$item";
	$echo .="_rem' placeholder='Remarques...'></textarea></div>";
	$echo .= "<div class='ui-block-c'></div><div class='ui-block-d'></div></div><br />";
	$echo .= "<!------->";
	$item++;
}
echo $echo;
$result->free_result();
?>

</div>

<!--......................................................      F O O T E R -->
    <div data-role="footer">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#phase2"data-transition="slidedown" data-icon="arrow-u">Phase 2</a></li>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>
			</ul>
		</div>
    </div>


</div>
<!--********************************************************************************************************-->
<!------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------- DIALOGS ----- Phase 3 Ex  --->
<!------------------------------------------------------------------------------------------------------------>
<?php 																						//	P H P
displayExemples($base, "3");
$base->close();
?>
	
<!-------------------------------------->
</body>
</html>

<!--<script>
	$(document).ready(function() {
		initScore();
	});
</script>-->
<!--<script src="scorescale.js"></script>-->
   
