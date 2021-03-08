<!DOCTYPE html>

<!--<?php session_start(); ?>-->
<html>
<head>
	<title>IsaScore</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=0" />
<!--	<meta name="viewport" content=" initial-scale=1.0 minimum-scale=0.66 maximum-scale=1.5, user-scalable=yes" />-->

	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />

<!--	<link rel="stylesheet" href="themes/isaTheme.min.css" />-->
	<link rel="stylesheet" href="lib/jquery.mobile-1.4.2.min.css" />

	<script src="lib/jquery-2.1.0.min.js"></script>
	<script src="scorescale.js"></script>
	<script src="lib/jquery.mobile-1.4.2.min.js"></script>

<!--																		S T Y L E    -->
	<link rel="stylesheet" href="isa.css">

	<script type="text/javascript">
    window.history.forward();
    function noBack() {
      window.history.forward();
    }
  </script>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

<?php																	//	P H P include
include_once("displayExemples.php");
include_once("connectMySQL.php");
include_once("queryDisplay.php");
$base = connectMySQL();
?>

<!--********************************************************************************************************-->
<!--*************************************************************************************** A C C U E I L ---->
<!--********************************************************************************************************-->
<div data-role="page" id="accueil">
	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

	<div data-role="popup" class="alertPopup ui-content" data-dismissible="true">
		<strong><p></p></strong>
	</div>

	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

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

		<div class="resumPass resumPass1-h"></div>

	  <div onclick="$.mobile.silentScroll(1000) /*$(document).scrollTop(1000)*/">
		<h1 class="accueilBigTitle" style="margin-bottop:30px;margin-top:20px;">Echelle d'imitation</h1>
		<!--Version 2014-->
		<br />
		<div><img src="images/intro.jpg" alt="image" width="250" /></div>
		<h4 style="margin-bottom:2px;">Jacqueline Nadel</h4>

		<p style="font-size:small; margin-top:2px;">CNRS USR3246</br>
		H&ocirc;pital de La Salp&ecirc;tri&egrave;re, Paris</br>
		e-mail:jacqueline.nadel@upmc.fr</p>

		<img src="images/logoSebISA.png" alt="Pas d'image" width="220" />
		<!--		<img src="images/iconClaire.png" alt="Pas d'icon" />-->
		<br /><code></code>
		<br />
	  </div>
<!--.................................................................... identification ...-->
		<div id="popupLogin" class="login" style="padding:10px 20px;">
			<h3 class="text-center ">Identifiez-vous</h3>
		    <input type="text" name="user" id="observateur" value="" placeholder="Observateur">

		    <input type="password" name="pass" id="password" value="" placeholder="Mot de passe">
			<br />
			<button id="login" class="action-button ">Connection</button>
		</div>

		<div id="changeUser" class="login" style="display:none;">
			<br /><br />
			<button id="changeUser" data-mini="false" class="action-button " onclick="changeObs('accueil');">Fermer la session</button>
		</div>
		<br />
    </div>
<!-- .............................................      F O O T E R-->

    <div data-role="footer" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#consigne" data-transition="slide" data-direction="reverse"  data-icon="arrow-l">Consigne</a></li>
				<li><a href="#record" data-transition="slideup" data-icon="arrow-d">Cotation</a></li>
				<li><a href="#query" data-transition="slide" data-icon="arrow-r">Passations</a></li>
			</ul>
		</div>
    </div>
</div>


<!--********************************************************************************************************-->
<!--******************************************************************************* S A U V E G A R D E  ----->
<!--********************************************************************************************************-->
<div data-role="page" id="record">

	<div data-role="popup" class="alertPopup ui-content" data-dismissible="true">
		<strong><p></p></strong>
	</div>

	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

	<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
		<strong><p></p></strong>
		<div>
			<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
			<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
		</div>
	</div>

<!--...............................................      H E A D E R  -->
    <div data-role="header">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>
				<li><a href="#query" data-transition="flip" data-icon="arrow-u-r">Passations</a></li>
			</ul>
		</div>
    </div>


<!------------------------------------------------------------------------------------------------------------------>
    <div role="main" class="ui-content">
	<div class="resumPass resumPass1"></div>
	<br />
	<h1 class="cotationTitle" style="margin:0px;">Cotation</h1>
	<h2 class="cotationTitle" style="margin:0px;">d'une passation</h2>

	<div class="padRecord">
<!------------------------------------------------------------------------------------------------------------------>
<!--		      															Formulaire de sauvegarde			  -->
		<div id="sauvegarde">
			<br />

			<button id="saveProto" disabled="disabled" class="action-button">Créer</button>
			<button id="reloadProto" disabled="disabled" class="action-button">Reprendre</button>
			<button id="closeProto" disabled="disabled" class="action-button"  onclick="closeProto();">Fermer</button>
			<br />

		<div id="cotationForm">
			<div class="ui-field-contain">
				<label for="participant">Participant:</label>
				<input type="text" id="participant" class="sauvegarde">
			</div>


			<div class="ui-field-contain">
				<label for="period">Période:</label>
				<input type="text" id="period" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="ageChrono">Age:</label>
				<input type="text" id="ageChrono" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="ageDev">Age (dév.):</label>
				<input type="text" id="ageDev" class="sauvegarde">
			</div>

<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" class="radio">
        <legend>Genre:</legend>
        <input type="radio" name="sex" id="girl" value="fille" class="sauvegarde">
        <label for="girl">&nbsp;&nbsp;Fille&nbsp;&nbsp;</label>
        <input type="radio" name="sex" id="boy" value="garçon" class="sauvegarde">
        <label for="boy">Garçon</label>
    </fieldset>

			<div class="ui-field-contain">
				<label for="location">Institution:</label>
				<input type="text" id="location" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="rem1">#1:</label>
				<input type="text" id="rem1" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="rem2">#2:</label>
				<input type="text" id="rem2" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="rem3">#3:</label>
				<input type="text" id="rem3" class="sauvegarde">
			</div>
			<div class="ui-field-contain">
				<label for="rem_libre">Remarques:</label>
				<textarea id="rem_libre" class="sauvegarde" rows="4"></textarea>
			</div>
			<br />
			<br />
		</div>

		</div>
	</div>
	</div>
<!--.............................................      F O O T E R-->

    <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#phase1" data-transition="slideup" data-icon="arrow-d">Phase 1</a></li>
				<li><a href="#phase2" data-transition="slideup" data-icon="arrow-d">Phase 2</a></li>
				<li><a href="#phase3" data-transition="slideup" data-icon="arrow-d">Phase 3</a></li>
			</ul>
		</div>
    </div>
</div>

<!--********************************************************************************************************-->
<!--******************************************************************************* Q U E R Y ---->
<!--********************************************************************************************************-->
<div data-role="page" id="query">

	<div data-role="popup" class="alertPopup ui-content" data-dismissible="true">
		<strong><p></p></strong>
	</div>


<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
	<strong><p></p></strong>
	<div>
		<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
		<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
	</div>
</div>

<!--...........................................  PANEL COLONNES ..-->
<div data-role="panel" id="chooseColsPanel" data-display="overlay" style="background:#F0F0F0; opacity:1;">
	<div id="colsBlock"></div>
	<div class="ui-body ui-body-a ui-corner-all">
		<input type="button" data-mini='true' data-inline='false' class="action-button" onclick="$(this).blur(); mailAttach('send', 'obs', 'mailUserParticipants.php');" data-icon="mail" value="Fichier CSV">
	</div>

	<div style="height:1000px" onmouseover="$(this).css({'cursor':'pointer'})" onclick="$('#chooseColsPanel').panel('close');">
	<br />
	<b style="color:#888; text-align:center !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fermer</b>
	</div>
</div>

<!--...........................................  PANEL ACTIONS ..-->
<div data-role="panel" id="chooseActionsPanel" data-display="overlay" data-position="right" style="background:#F0F0F0; opacity1;">
	<br />
	<div id="actualParPanel" onmouseover="$(this).css({'cursor':'pointer'})" onclick="$('#chooseActionsPanel').panel('close');"></div>
	<button id="reloadPar" class="action-button" data-mini='true'>Reprendre la cotation</button>
	<button id="duplicatePar" class="action-button" data-mini='true'>Dupliquer</button>
	<button id="deletePar" class="action-button" data-mini='true'>Supprimer</button>
	<br />
	<div class="ui-body ui-body-a ui-corner-all">
		<input type="button" data-mini='true' data-inline='false' class="action-button" onclick="$(this).blur(); mailAttach('html', $('#chooseActionsPanel').attr('data-isa-id'), 'mailDetail.php');" data-icon="mail" value="Télécharger">
	</div>

	<div style="height:1000px" onmouseover="$(this).css({'cursor':'pointer'})" onclick="$('#chooseActionsPanel').panel('close');">
	<br /><br />
	<b style="color:#888; text-align:center !important;">&nbsp;&nbsp;&nbsp;&nbsp;Fermer</b>
	</div>
</div>


<!--...................  pop up ACTION sur passation ......... Hors Service .................
<div data-role="popup" id="queryPopupMenu">
    <ul data-role="listview" data-inset="true" style="min-width:210px;">
		<li data-role="list-divider">Passation</li>
        <li><a href="#" id="detailAncor" data-transition="slide" class="ui-btn" onclick="doClickActionPopup('showFullData');">Tout afficher</a></li>
        <li><a href="#" data-rel="back" class="ui-btn" onclick="doClickActionPopup('dupliqueObservation');">Dupliquer la passation</a></li>
        <li><a href="#" data-rel="back" class="ui-btn" onclick="doClickActionPopup('deleteObservation');">Supprimer la passation</a></li>
    </ul>
</div>-->
<!--.............................................-->
<!--.............................................-->
<div role="main" class="ui-content">
		<div class="resumPass resumPass1-h"></div>
<!--		<h2 class="queryTitle">Passations</h2>-->
		<br />
		<div class="loader" style="display:none">
			<img src="images/loaderB32.gif">
		</div>


<!--		***************************************        	-->
<!--						GABARITS   BOUTONS  				 -->
<!--		 ******************************************       -->

<div class="qrTable text-center">
	<!--.............................................  Dernières connections -->
	<div id="qrConnections" style="display:none">
		<div>
			<a data-role='button'  href='#' id='qConnections' data-inline='true' style="color:crimson;">Dernières connections</a>
		</div>
		<div id='cConnections'></div>
		<div id='rConnections'></div>
	</div>
	<!--.............................................   PASSATIONS DE <observateur> -->
	<div id='qrUserParticipants' style='display:none;'>
		<div>
			<a data-role='button'  href='#' id='qUserParticipants' data-inline='true'></a>
		</div>

		<div id='cUserParticipants' style='display:none;' class='padRecord'>

		</div>
	</div>
	<div id='rUserParticipants'></div>
	<!--............................................. Dernières passations  -->
	<div>
		<a data-role='button'  href='#' id='qResum' title='Afficher les 25 dernières passations' data-inline='true'>Dernières passations</a>
	</div>
	<div id="rResum"></div>

	<!--.............................................  Observateurs -->
	<div>
		<a data-role='button'  href='#' id='qUsers' title='Afficher la liste des observateurs' data-inline='true'>Observateurs</a>
	</div>
	<div id="rUsers"></div>
	<!--.............................................  Etablissements -->
	<div>
		<a data-role='button'  href='#' id='qLocations' title='Afficher la liste des établissements' data-inline='true'>Etablissements</a>
	</div>
	<div id="rLocations"></div>
	<!--.............................................-->


	<!--.............................................-->
</div>


<br /><br /><br /><br />
</div>

<!--.............................................      F O O T E R-->

    <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#accueil" data-transition="slide" data-direction="reverse" data-icon="home">Accueil</a></li>
				<li><a href="#record" data-transition="flip" data-icon="arrow-d-l">Cotation</a></li>
			</ul>
		</div>
    </div>

</div>
<!--.............................................  					PAGE DETAILS_DIALOG  -->
<!--.............................................-->

<div data-role="page" id="detailDialog">

<div class="resumPass resumPass1-h"></div>
<!--........................................  alert popup .....-->
	<div data-role="popup" class="alertPopup ui-content" data-dismissible="true">
		<strong><p></p></strong>
	</div>
<!--........................................  confirm popup .....-->
	<div data-role="popup" class="confirmPopup ui-content" data-dismissible="false">
		<strong><p></p></strong>
		<div>
			<a href="#" class="confirmCancel ui-btn ui-corner-all ui-shadow" data-rel="back">Annuler</a>
			<a href="#" class="confirmOK ui-btn ui-corner-all ui-shadow" data-rel="back" data-transition="flow"></a>
		</div>
	</div>

<!--.........................-->

	<div role="main" class="ui-content">
<!--.........................-->

		<div style='text-align:left;'>
		<br />

<!--......................................... mini panel flottant ...-->

			<div class="miniPanelDiv" onmouseover="$(this).css({'cursor':'pointer'})" onclick="detailMiniPanelShow();">
				<a href='#' data-mini='true' class="miniPanelButton ui-btn ui-btn-inline ui-corner-all ui-icon-action ui-btn-icon-notext" data-icon="action" data-icon-text="notext"></a>
			</div>
			<div id="detailMiniPanel" class="miniPanel ui-body ui-body-a ui-corner-all">
				<button id="detailReloadButton" class="action-button" data-mini='true'>Reprendre</button>
				<button id="detailDuplicateButton" class="action-button" data-mini='true'>Dupliquer</button>
				<button id="detailDeleteButton" class="action-button" data-mini='true'>Supprimer</button>
				<br />
				<input type="button" data-mini='true' data-inline='false' class="action-button poster-button" onclick="mailAttach('html', $('#detailDialog').attr('data-isa-id'), 'mailDetail.php');" data-icon="mail" value="Télécharger">
			</div>
<!--.........................-->
			<code></code>
			<br />
		</div>
<!--.........................-->
	</div>
<!--.........................-->
    <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#query" data-transition="slide" data-direction="reverse" data-icon="arrow-l">Passations</a></li>
				<li><a href="#accueil" data-transition="slide" data-direction="reverse" data-icon="home">Accueil</a></li>
				<li><a href="#record" data-transition="flip" data-icon="arrow-d-l">Cotation</a></li>
			</ul>
		</div>
    </div>
</div>
<!--.............................................-->


<!--********************************************************************************************************-->
<!--******************************************************************************************** CONSIGNE ---->
<!--********************************************************************************************************-->
<div data-role="page" id="consigne">
<!--....................................................       -->
	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>


    <div role="main" class="ui-content">

		<div class="resumPass resumPass1-h"></div>

		<div class="contentTextList">
		<h2>Consigne de passation</h2><br />
<h4>L'outil d'évaluation est composé de 3 sous échelles comprenant chacune 12 items</h4><br />
1-	<i>Imitation spontanée,</i><br />
2-	<i>Reconnaissance d'être imité</i><br />
3-	<i>Imitation sur requête en utilisant la consigne « fais comme moi »</i><br /><br />

La sous échelle 3 doit impérativement être passée en dernier (sinon on ne sait plus ce qui est spontané). Par contre si l'imitation spontanée ne se déclenche pas, on peut débloquer la situation en passant à la sous échelle 2 puis revenir ensuite à la 1.<br /><br />


L'ordre <u>à l'intérieur de chaque sous échelle</u> est plus flexible. On peut avoir oublié un item et y revenir ensuite, ou préférer un item parce que l'enfant est plus familier de l'action proposée. Toutefois celui que nous indiquons est ludique (ce qui est très important) et largement testé. Il  permet d'éviter une succession de gestes non significatifs, ce qui n'est pas conseillé.<br /><br />


Deux actions sont proposées pour certains items au choix de l'examinateur.<br />
</br >
<h2>Les objets</h2>

<?php
													//     liste objets
//	P H P
$query = "select phrase from Item where destination = 'listeObjs' order by rang";
$result = $base->query($query);

$toEcho = '<div data-role="collapsible-set">'; // '<div  data-role="collapsible-set">';

$item = 1;
while ($row = $result->fetch_assoc()) {
	$phrase = htmlentities($row["phrase"]);

	$toEcho .= "<div data-role='collapsible'>";   //  data-mini='true'
	$toEcho .= "<h4>$phrase</h4>";
	$toEcho .= "<div class='media-width text-center'><br /><strong class='center'>$phrase</strong><br /><div class='media-contener center'><img src='images/objet$item.png' alt=\"Pas d'image!\" class=\"media-width\" /></div></div>";
	$toEcho .= "</div>";

	$item++;
}
$toEcho .= "</div>";
echo $toEcho;
?>
</div>
	</div>
<!--...............................................      F O O T E R  -->
  <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
<!--				<li><a href="#listeObjs"data-transition="slidedown" data-icon="arrow-u">Objets</a></li>-->
			<li><a href="#accueil" data-transition="slide" data-icon="home">Accueil</a></li>
<!--				<li><a href="#phase1" data-transition="slideup" data-icon="arrow-d">Phase 1</a></li>-->
			</ul>
		</div>
  </div>
</div>

<!--********************************************************************************************************-->
<!--************************************************************ IMITATION SPONTANÉE  ************ PHASE 1---->
<!--********************************************************************************************************-->
<div data-role="page" id="phase1" data-dom-cache="false">
	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

<!--...............................................      H E A D E R  -->
  <div data-role="header">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>
				<li><a href="#record" data-transition="slidedown" data-icon="arrow-u">Cotation</a></li>
				<li><a href="#query" data-transition="flip" data-icon="arrow-u-r">Passations</a></li>
			</ul>
		</div>
  </div>

<!--.........................................      C O N T E N T
-->
<div role="main" class="ui-content">

	<br />
	<div class="resumPass resumPass1"></div>
	<div class="resumPass resumPass2" style="display:none">Phase 1</div>
	<h1 class= "cotationTitle" >Phase 1</h1>
	<h2 class="scoring">Imitation Spontanée</h2>
	<br />

	<div class="contentTextList">
		<h4>Consignes de cotation:</h4>

		<p>0 = Aucun intérêt manifesté pour l'objet</p>
		<p>1 = Mouvement non imitatif de la partie du corps concerné: intérêt manifesté pour l'objet (regard, mouvement ou  amorce de mouvement vers l'objet)</p>
		<p>2 = Imitation partielle (imitation impliquant une autre partie du corps, imitation sur un autre objet, tentative non aboutie), imitation d'une partie de la séquence seulement</p>
		<p>3 = Imitation réussie</p>
		<br />
	</div>		<!----- fin contentTextList -->

<!-------------------------------->

	<?php																				//	P H P
		displayExemples("1");
	?>

<!------->
	</div>
  <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#phase2" data-transition="slideup" data-icon="arrow-d">Phase 2</a></li>
				<li><a href="#phase3" data-transition="slideup" data-icon="arrow-d">Phase 3</a></li>
			</ul>
		</div>
  </div>

</div>


<!--********************************************************************************************************-->
<!--********************************************************************************************* PHASE 2 ---->
<!--********************************************************************************************************-->

<div data-role="page" id="phase2" data-dom-cache="false">
	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

<!--...............................................      H E A D E R  -->
    <div data-role="header">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>
				<li><a href="#record" data-transition="slidedown" data-icon="arrow-u">Cotation</a></li>
				<li><a href="#query" data-transition="flip" data-icon="arrow-u-r">Passations</a></li>
			</ul>
		</div>
    </div>


<!--.....................................................      C O N T E N T -->
	<div role="main" class="ui-content">

		<br />
		<div class="resumPass resumPass1"></div>
		<div class="resumPass resumPass2" style="display:none">Phase 2</div>
		<h1 class= "cotationTitle" >Phase 2</h1>
		<h2 class="scoring">Reconnaissance d'être imité</h2>
		<br />

		<div class="contentTextList">
			<h4>Consignes de cotation:</h4>

			<p>0 = Aucun intérêt manifesté pour l'imitation de l'expérimentateur</p>
			<p>1 = Touche, embrasse, regarde, sourit, vocalise, se rapproche ...</p>
			<p>2 = Reconnaissance avec test: change d'objet ou de mouvement en regardant ce que fait l'expérimentateur (teste l'intention d'imiter)</p>
			<p>3 = Reconnaissance menant à un tour de rôle: regarde ce que fait l'expérimentateur (E), puis imite à son tour et refait ensuite une autre proposition d'activité en regardant l'E (en général en souriant: il s'agit de communication)</p>

			<br />
			<p>L'expérimentateur peut produire différents types d'imitation selon ce que fait le sujet, mais doit au moins avoir fait deux essais de trois imitations:<br /> par exemple une posture, une imitation d'action familière et un geste non significatif, à 2 moments différents,  ou trois imitations d'action familère à deux moments différents, plus il y a de types d'imitation reconnus, meilleure est la reconnaissance
			Si l'enfant ne produit que des stéréotypies, il faut être prudent car les imiter peut mener parfoisà une réaction violente.</p><br />
		</div>
	<!-------------------------------->

	<?php																				//	P H P
	displayExemples("2");
	?>

	</div>
<!-- .............................................      F O O T E R-->
  <div data-role="footer">
		<div data-role="navbar" data-iconpos="bottom">
			<ul>
				<li><a href="#phase1" data-transition="slideup" data-icon="arrow-d">Phase 1</a></li>
				<li><a href="#phase3" data-transition="slideup" data-icon="arrow-d">Phase 3</a></li>
			</ul>
		</div>
  </div>
</div>


<!--********************************************************************************************************-->
<!--********************************************************************************************* PHASE 3 ---->
<!--********************************************************************************************************-->

<div data-role="page" id="phase3" data-dom-cache="false">
	<div class="loader" style="display:none"><img src="images/loaderB32.gif" /></div>

<!--...............................................      H E A D E R  -->
    <div data-role="header">
		<div data-role="navbar" data-iconpos="top">
			<ul>
				<li><a href="#accueil" data-transition="slidedown" data-icon="home">Accueil</a></li>
				<li><a href="#record" data-transition="slidedown" data-icon="arrow-u">Cotation</a></li>
				<li><a href="#query" data-transition="flip" data-icon="arrow-u-r">Passations</a></li>
			</ul>
		</div>
    </div>


<!--.....................................................      C O N T E N T -->
	<div role="main" class="ui-content">

		<br />
		<div class="resumPass resumPass1"></div>
		<div class="resumPass resumPass2" style="display:none">Phase 3</div>
		<h1 class= "cotationTitle" >Phase 3</h1>
		<h2 class="scoring">Imitation sur demande</h2>
		<br />

		<div class="contentTextList">
			<h4>Consignes de cotation:</h4>

			<p>0 = Aucun intérêt manifesté pour l'objet</p>
			<p>1 = Intérêt manifesté pour l'objet (regard, mouvement ou amorce de mouvement vers l'objet, mouvement non imitatif de la partie du corps concerné)</p>
			<p>2 = Imitation partielle (imitation impliquant une autre partie du corps, imitation sur un autre objet, tentative non aboutie), imitation d'une partie de la séquence seulement</p>
			<p>3 = Imitation réussie</p>
			<br />
		</div>
	<!-------------------------------->

		<?php																					//	P H P
		displayExemples("3");
		?>
	</div>

<!-- .............................................      F O O T E R-->
  <div data-role="footer">
	<div data-role="navbar" data-iconpos="bottom">
		<ul>
			<li><a href="#phase1" data-transition="slideup" data-icon="arrow-d">Phase 1</a></li>
			<li><a href="#phase2" data-transition="slideup" data-icon="arrow-d">Phase 2</a></li>
		</ul>
	</div>
  </div>

</div>

<!-------------------------------------->
</body>
<!-- bricolage hide-show footer, pas de footer en mode paysage!!!
          window.screen.width < 1200                 -->

<script type="text/javascript"> if (isMobile())  document.write( '<style>div[data-role="footer"]{visibility:hidden}@media(min-height:' + ($( window ).height() - 10) + 'px){div[data-role="footer"]{visibility:visible}}</style><style>div[data-role="header"]{visibility:hidden}@media(min-height:' + ($( window ).height() - 10) + 'px){div[data-role="header"]{visibility:visible}}</style>' ); </script>

</html>
