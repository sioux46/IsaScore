//                      scorescale.js


var TIME_OUT = 500;
var TIME_OUT_MINI = TIME_OUT / 2;
var POPUP_BG = "#FFFFEE";  //"#F0F8FF";
var POPUP_OK = "crimson";   //"limeGreen";
var POPUP_CANCEL =  "gray";
var BUTTON_TEXT = "dimGray";
var BUTTON_BORDER = "crimson";  // "#F88";
var FOOTER_TEXT = "crimson"; // "darkSlateGray";
var MESSAGE_COLOR = "limeGreen";
var PHASE_LINE1 = 34;
var PHASE_LINE2 = 116;

var observateur = "";
var reloadId = 0;
var backgroundSaveOn = false;
var saisieOn = false;
var protocole = {};


var version = 'v0.36-49';  //   V E R S I O N

protocole.version = '[' + version + ']' + navigator.platform + ' ' + navigator.userAgent;

//*****************************************************************************************
//*****************************************************************************************
//****************************************************************** F U N C T I O N S ****
//*****************************************************************************************
function initIsa() {
	observateur = "";
	reloadId = 0;
	backgroundSaveOn = false;
	verifChooseCols();
	protocole = { observateur:"", participant:"", ageDev:"", ageChrono:"", sex:"", period:"", location:"", rem1:"", rem2:"", rem3:"", rem_libre:"" };
	verifChooseCols();
	initProto();
//*****************************************************************************************
}
function closeProto() {
	if (savePending()) {
		alertIsa('Pas de réseau!', 'crimson', false);
		return;
	}
	initProto();
	saisieOn = false;
	$("#cotationForm").fadeOut(TIME_OUT);
	$("#saveProto").removeAttr("disabled");
	$("#reloadProto").removeAttr("disabled");
	$("#closeProto").attr({"disabled":"disabled"});
}
//*****************************************************************************************
function onOfSaisie(page) {
	if (!saisieOn) {   //  bloquer saisie
		$(page).find(".saisie textarea").attr({"disabled":"disabled"});
		$(page).find(".saisie select").selectmenu("disable");
	}
	else {  		 //  débloquer saisie
		$(page).find(".saisie textarea").removeAttr("disabled").removeClass("ui-state-disabled");
		$(page).find(".saisie select").selectmenu("enable");
	}

}
//*****************************************************************************************

function initProto() {
	reloadId = 0;

    $(".saisie select").val("-1");
    $(".saisie textarea").val("");

    $(".saisie a[id$='-button'] span").text(' - ');
    $(".saisie select, .saisie textarea").each(function() {
        protocole[this.id] = $(this).val();
    });

	$("#participant").val(""); protocole.participant = "";
	$("#period").val(""); protocole.period = "";
	$("#ageChrono").val(""); protocole.ageChrono = "";
	$("#ageDev").val(""); protocole.ageDev = "";

	$("#record input[id='girl']").attr({"checked":false}).attr({"value":"fille"});
	$("#record input[id='boy']").attr({"checked":false}).attr({"value":"garçon"});

	$("#record label[for='girl']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");
	$("#record label[for='boy']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");

	protocole.sex = "";

	$("#location").val(""); protocole.location = "";
	$("#rem1").val(""); protocole.rem1 = "";
	$("#rem2").val(""); protocole.rem2 = "";
	$("#rem3").val(""); protocole.rem3 = "";
	$("#rem_libre").val(""); protocole.rem_libre = "";
	protocole.version = '[' + version + ']' + navigator.platform + ' ' + navigator.userAgent;

	$(".resumPass").css({'color':'limeGreen', 'background':'rgba(0,255,0,0.1)'});
	$(".resumPass").attr({'data-isa-pending':false});
	headerText();


//	window.onbeforeunload = null;
//    $(".saisie select").on("change", function () {
//        protocole[this.id] = $(this).val();

//        if (!window.onbeforeunload) {
//            window.onbeforeunload = function() {
//                return "Si vous quittez maintenant, la passation courante sera registrée avec \"???" + protocole.participant + "???\" comme nom de participant";
//            }
//		}
//    });

}
//*****************************************************************************************
function protoVide() {
	var vide = true;
	$(".saisie select").each(function() {
		if ($(this).val() != "-1") vide = false;
	});

	$(".sauvegarde").each(function() {
		if ($(this).val() !== "") vide = false;
	});

	return vide;
}
//*****************************************************************************************
function headerText() {
	var q1, q2;
	if (!protocole.observateur) return;
	if (reloadId) {
		q1 = '<';
		q2 = '>';
	}
	else {
		q1 = '';
		q2 = '';
	}
  $(".resumPass1, .resumPass1-h").text("[" + protocole.observateur + "]" + "  " + q1 + protocole.participant + q2 + "  " + protocole.rem1 + "  " + protocole.rem2 + "  " + protocole.rem3);
	$(".resumPass2, resumPass2-h").show();

	if (protocole.observateur == 'superisa') $(".resumPass").css({'color':'black'});
}
//*****************************************************************************************
function qAjaxTable(item, click, colName) { // item: nom de la commande (ex: Users)
                                            // click: flag 'clickable'
                                            // colName: vrai nom col. tri OU vide

  var selector = '#r' + item;
  var selector2 = '#c' + item;
  var url = 'q' + item + '.php';
  var clickItem = 'c' + item + '.php';
	var order = findColNameOrder(colName);       // order: ordre du tri (asc ou desc)

  if (($(selector + ' table').css("display") == "table") && (!colName)){
		$(selector + ' table').show().fadeOut(TIME_OUT);
    $(selector2).hide();
  }
  else {
	  $("#cUserParticipants div").css({"border":"0px"});

	  var buildRequete = buildChooseColsRequete();
	  var requete = buildRequete.requete;
	  var data = buildRequete.data;
    $.ajax({
      url: url,
      type: 'POST',
      data: {'colName':colName, 'user':observateur, 'requete':requete, 'data':data, 'order':order},
      complete: function(xhr, result) {
	      $(".loader").hide();
        if (result != 'success') {
          alertIsa('Pas de réseau!\n', 'crimson', false);
        }
        else {
            $(selector2).show();
		      $(selector).html(xhr.responseText);
		      $(selector + ' table').hide().slideDown(TIME_OUT);

          if (click) {
            $(selector + " table td").parent("tr").attr({"onmouseover":"$(this).css({'cursor':'pointer'})", "onclick":"if (event.target.nodeName!='A') clickItem($(this).attr('data-ISAobsId'), '" + item + "', false);"});
			      $(selector + " th").attr({"onmouseover":"$(this).css({'cursor':'pointer'})", "onclick":"$(this).css({'color':'steelBlue'})"});
		      }
		      if (order == 'DESC')
			        $(selector + " div[data-ISAcolName='" + colName + "']").parent("th").css({"border-color":"limeGreen", "border-top-color":"white"});
		      else
			        $(selector + " div[data-ISAcolName='" + colName + "']").parent("th").css({"border-color":"limeGreen", "border-bottom-color":"white"});

          $(selector).hide().slideDown(TIME_OUT);
        }
      }
    });
  }
}
//**********************************************************************************************
function openUserParticipants() {
	if (savePending()) return;
	if ($("#rUserParticipants table").css("display") == "table") refreshUserParticipants();
	else {
		backgroundSaveOn = true;
		qAjaxTable('UserParticipants', true, '');
	}
}
//**********************************************************************************************
function refreshUserParticipants() {
	if (savePending()) return;
	backgroundSaveOn = true;
	qAjaxTable('UserParticipants', true, '');
	setTimeout(function () {
		backgroundSaveOn = true;
		qAjaxTable('UserParticipants', true, '');
	}, TIME_OUT * 2);
}
//*****************************************************************************************
function findColNameOrder(colName) {
	if (!colName) return '';
	if (!sessionStorage.colNameOrder) sessionStorage.colNameOrder = JSON.stringify({});
	var colNameOrder = JSON.parse(sessionStorage.colNameOrder);
	if (!colNameOrder[colName]) {
		colNameOrder[colName] = 'DESC';
	}

	var order = colNameOrder[colName];
	if (order == 'DESC') {
		colNameOrder[colName] = 'ASC';
	}
	else {
		colNameOrder[colName] = 'DESC';
	}

	sessionStorage.colNameOrder = JSON.stringify(colNameOrder);
	return order;
}
//**********************************************************************************************
function clickItem(data, item, fromDel) { //  data: id passation, item: nom de la commande (ex: Users)
	if (fromDel) return;
	if (item != 'UserParticipants') return;  // seule commande implémentée
	var selector2 = '#r' + item + " tr[data-ISAobsId='" + data + "']";
	if ($(selector2).next().children().is("td[colspan]")) {
//        if (fromDel) return;
        $(selector2).next().fadeOut(TIME_OUT);
		setTimeout(function () {
            $(selector2).next().remove();
            $(selector2).css({'color':'black'});
        }, TIME_OUT);

		return;
	}
	else $(selector2).css({'color':'steelBlue'}); //  limeGreen

    var selector = '#c' + item;
    var url = 'c' + item + '.php';
	var nbCols = $("#query table[data-ISAitem='" + item +"']").get(0).rows[0].cells.length - 2;
    $.ajax({
        url: url,
        data: {data:data, nbCols:nbCols},
        complete: function(xhr, result) {
			$(".loader").hide();
            if (result != 'success') {
                alertIsa('Pas de réseau!\n', 'crimson', false);
            }
            else {                              //  afficher mini détails
                var reponse = xhr.responseText;
				$(selector2).after(reponse);

				$(selector2).on("swipeleft", function() {
                    var clickActionId = $(this).attr('data-ISAobsId');
                    doClickActionPopup('showFullData', clickActionId);
                });

                $(selector2).next().on("click swipeleft", function() {
                    var clickActionId = $(this).prev().attr('data-ISAobsId');
                    doClickActionPopup('showFullData', clickActionId);
                }).on("mouseover", function() {
                    $(this).css({'cursor':'pointer'});
                }).attr('title','Afficher toutes les données');
				$(selector2).next().css({'background':'#FFFFF9'}).fadeIn(TIME_OUT);
            }
        }
    });
}
//*****************************************************************************************
function doClickActionPopup(action, clickActionId) { // action: (showFullData, deleteObservation, duplicateObservation)
    if ((action != 'deleteObservation') && (action != 'duplicateObservation'))  doClickAction(action, clickActionId);
    else {
		if ((action == 'deleteObservation') && (clickActionId == reloadId)) {
			alertIsa('Suppression impossible (Cotation en cours)', 'crimson', false);
			return;
		}
		else {
			var message;
			if (action == 'deleteObservation') message = 'Supprimer';
			else message = 'Dupliquer';
			$($.mobile.activePage).find(".confirmPopup p").css({"color":MESSAGE_COLOR}).text( message + " la passation ?");
			$($.mobile.activePage).find(".confirmOK").text(message);
			$($.mobile.activePage).find(".confirmOK").off("click").on("click", function () {
				doClickAction(action, clickActionId);
			});
			$($.mobile.activePage).find(".confirmPopup").popup("open");
		}
	}
}
//*****************************************************************************************
function doClickAction(action, clickActionId) { // action: nom action  (item menu)
    $.ajax({
        url: 'clickActionPopup.php',
        data: {action:action,id:clickActionId}, // id passation
        complete: function(xhr, result) {
			$(".loader").hide();
            if (result != 'success') {
                alertIsa('Pas de réseau!\n', 'crimson', false);
            }
            else {
				$( "#chooseActionsPanel" ).panel( "close" );
                var reponse = xhr.responseText;
                if (action == 'deleteObservation') {   //  SUPPRIMER PASSATION
                    var selector2 = '#rUserParticipants' + " tr[data-ISAobsId='" + clickActionId + "']";
                    if ($(selector2).next().children().is("td[colspan]")) {
                        $(selector2).next().fadeOut(TIME_OUT);
                        setTimeout(function () {
                            $(selector2).next().remove();
                            $(selector2).css({'color':'black'});
                        }, TIME_OUT);
                    }
                    $(selector2).fadeOut(TIME_OUT);
                    setTimeout(function () {
                        $(selector2).remove();
                        alertIsa('Passation supprimée', MESSAGE_COLOR, true);

                    }, TIME_OUT);
                }
                if (action == 'showFullData') {    //   AFFICHER LES DETAILS
					$("#detailDialog code").html(reponse);
					$("#detailDialog").attr({"data-isa-id":clickActionId}).css({'background':'#FFFFF9'})
                    $("body").pagecontainer("change", "#detailDialog", {transition: 'slide'});
                }
				if (action == 'duplicateObservation') {
					if (reponse != 0) {
						refreshUserParticipants();
						setTimeout(function () {
							alertIsa('Passation dupliquée', MESSAGE_COLOR, true);
						}, TIME_OUT);
					}
				}
            }
		}
	});
}
//**********************************************************************************************
function alertIsa(texte, color, shortTime) {
	var time;
	if (shortTime) time = TIME_OUT * 3;
	else time = TIME_OUT * 8;
	$(".loader").hide();
    $($.mobile.activePage).find(".alertPopup p").css({"color":color}).text(texte);
    $($.mobile.activePage).find(".alertPopup").css({"display":"block"}).popup("open");
	setTimeout(function () {
		$($.mobile.activePage).find(".alertPopup").fadeOut(TIME_OUT);
		setTimeout(function () {
			$($.mobile.activePage).find(".alertPopup").popup("close");
		}, TIME_OUT);
	}, time);
}
//**********************************************************************************************
function changeObs() {
	$.ajax({
		url: 'qUsers.php',
		timeout: TIME_OUT * 4,
		complete: function(xhr, result) {
			$(".loader").hide();
			if (result != 'success') {
				alertIsa('Pas de réseau! Fermeture impossible', 'crimson', false);
				return;
			}
			else {
				$($.mobile.activePage).find(".confirmPopup p").css({"color":MESSAGE_COLOR}).text("Fermer la session " + protocole.observateur + " ?");
				$($.mobile.activePage).find(".confirmOK").text("Fermer");
				$($.mobile.activePage).find(".confirmOK").off("click").on("click", function () {
					window.location.replace("../index.html");
				});
				$($.mobile.activePage).find(".confirmPopup").popup("open");
			}
		}
	});
}
//**********************************************************************************************
function updateIsa() {
    $($.mobile.activePage).find(".confirmPopup p").css({"color":MESSAGE_COLOR}).text("Mettre à jour IsaScore ?");
	$($.mobile.activePage).find(".confirmOK").text("Mettre à jour");
    $($.mobile.activePage).find(".confirmOK").off("click").on("click", function () {
		window.location.replace("../index.html");

//		$("body").pagecontainer("change", "../index.html", {reload: true});   //  ../index.html


//	$('body').pagecontainer( "change", "index.php" , { reload: true, reloadPage: true, allowSamePageTransition: true} );
//		$("body").pagecontainer("change", $.mobile.path.getDocumentUrl(), {reload: true, allowSamePageTransition: true, changeHash: false, dataUrl: $.mobile.path.getDocumentUrl()});
    });

    $($.mobile.activePage).find(".confirmPopup").popup("open");
}

//**********************************************************************************************
function mailAttach(action, data, url) {

	  if (( window.navigator.userAgent.indexOf('iPhone') != -1 ) ||
      ( window.navigator.userAgent.indexOf('iPad') != -1 ) ||
      ( window.navigator.userAgent.indexOf('Android') != -1 ) ) {
        alertIsa('Pas de téléchagement sur mobile!', 'crimson', false);
        return;
      }

		var theData = data;
		if (data == 'obs') theData = observateur;
		$(".loader").show();

		$.ajax({
			'url': url,
			'data': { 'data':theData, mail: storedMail() },
			'complete': function(xhr, result) {
				$(".loader").hide();
				if (result != 'success') {
					alertIsa('Pas de réseau!\n', 'crimson', false);
				}
				else {
          if ( action == 'html') {
            window.location = 'doDownloadHTMLFile.php';
          }
          else {
            window.location = 'doDownloadCSVFile.php';
          }
          alertIsa('Fichier téléchargé!', MESSAGE_COLOR, false);
				}
			}
    });
}
//**********************************************************************************************
function storedMail() {
	if (!localStorage.ISAstoredMail) localStorage.ISAstoredMail = "";
	return localStorage.ISAstoredMail;
}
//**********************************************************************************************
function storeMail(mail) {
	localStorage.ISAstoredMail = mail;
}
//*****************************************************************************************
function backgroundSave() {
	if (reloadId === 0) return;
	if (!(protocole.observateur)) return;
	if (protocole.observateur == "superisa") return;
	if (protoVide()) return;


	$(".resumPass").css({'color':'crimson', 'background':'rgba(255,0,0,0.1)'});
	$(".resumPass").attr({'data-isa-pending':true});
	protocole.reloadId = reloadId;
	if (reloadId > 0) backgroundSaveOn = true;
	$.ajax({
		url: 'saveProto.php',
		type: 'POST',
		data: protocole,
		timeout: TIME_OUT * 10,
		complete: function(xhr, result) {
			$(".loader").hide();
			if (result != 'success') {
				$(".resumPass").css({'color':'crimson', 'background':'rgba(255,0,0,0.1)'});
				$(".resumPass").attr({'data-isa-pending':true});
			}
			else {
				var reponse = xhr.responseText;
				if (reponse != 'OK') {
					$(".resumPass").css({'color':'crimson', 'background':'rgba(255,0,0,0.1)'});
					$(".resumPass").attr({'data-isa-pending':true});
				}
				else {
					$(".resumPass").css({'color':'limeGreen', 'background':'rgba(0,255,0,0.1)'});
					$(".resumPass").attr({'data-isa-pending':false});
					refreshUserParticipants();
					headerText();
				}
			}
		}
	});
}
//.......................
setInterval(function () {
	if (savePending()) backgroundSave();
}, TIME_OUT * 20);

//**********************************************************************************************
function reloadPassation(id) {
	if (protocole.observateur == 'superisa') return;
	if (savePending()) {
		alertIsa('Pas de réseau', 'crimson', false);
		return;
	}

	$(".resumPass").css({'color':'limeGreen', 'background':'rgba(0,255,0,0.1)'});
	$(".resumPass").attr({'data-isa-pending':false});

	doReloadPassation(id);
}
//**********************************************************************************************
function doReloadPassation(id) {

	$.ajax({
		url: 'reloadPassation.php',
		data: { 'id': id },
		complete: function(xhr, result) {
			$(".loader").hide();
			if (result != 'success') {
				alertIsa('Pas de réseau!\n', 'crimson', false);
			}
			else {
				reloadId = id;

				var reponse = JSON.parse(xhr.responseText);

				$("#participant").val(reponse.participant); protocole.participant = reponse.participant;
				$("#location").val(reponse.location); protocole.location = reponse.location;
				$("#period").val(reponse.period); protocole.period = reponse.period;
				$("#ageChrono").val(reponse.ageChrono); protocole.ageChrono = reponse.ageChrono;
				$("#ageDev").val(reponse.ageDev); protocole.ageDev = reponse.ageDev;

				if (reponse.sex == 'fille') {
					$("#record input[id='girl']").attr({"checked":true}).attr({"value":"fille"});
					$("#record input[id='boy']").attr({"checked":false}).attr({"value":"garçon"});

					$("#record label[for='girl']").removeClass("ui-radio-off").addClass("ui-radio-on").addClass("ui-btn-active");
					$("#record label[for='boy']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");
				}
				else if (reponse.sex == 'garçon') {
					$("#record input[id='girl']").attr({"checked":false}).attr({"value":"fille"});
					$("#record input[id='boy']").attr({"checked":true}).attr({"value":"garçon"});

					$("#record label[for='boy']").removeClass("ui-radio-off").addClass("ui-radio-on").addClass("ui-btn-active");
					$("#record label[for='girl']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");
				}
				else {
					$("#record input[id='girl']").attr({"checked":false}).attr({"value":"fille"});
					$("#record input[id='boy']").attr({"checked":false}).attr({"value":"garçon"});

					$("#record label[for='girl']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");
					$("#record label[for='boy']").removeClass("ui-btn-active").removeClass("ui-radio-on").addClass("ui-radio-off");
				}
				protocole.sex = reponse.sex;

				$("#rem1").val(reponse.rem1); protocole.rem1 = reponse.rem1;
				$("#rem2").val(reponse.rem2); protocole.rem2 = reponse.rem2;
				$("#rem3").val(reponse.rem3); protocole.rem3 = reponse.rem3;
				$("#rem_libre").val(reponse.rem_libre); protocole.rem_libre = reponse.rem_libre;
				protocole.version = '[' + version + ']' + navigator.platform + ' ' + navigator.userAgent;
				var indexRem, indexScore;
				for (var phase = 1; phase <= 3; phase++) {
					for (var item = 1; item <= 12; item++) {
						indexRem = 'ph' + phase + '_' + item + '_rem';
						protocole[indexRem] = reponse[indexRem];
						indexScore = 'phase' + phase + '_score' + item + '_';
						protocole[indexScore + 'A'] = reponse[indexScore + 'A'];
						protocole[indexScore + 'B'] = reponse[indexScore + 'B'];

						$(".saisie textarea[id='" + indexRem + "']").val(reponse[indexRem]);
						$(".saisie select[id='" + indexScore + "A']").val(reponse[indexScore + 'A']);
						$(".saisie select[id='" + indexScore + "B']").val(reponse[indexScore + 'B']);

						if (reponse[indexScore + 'A'] == -1)
							$(".saisie a[id$='" + indexScore + "A-button'] span").text(' - ');
						else
							$(".saisie a[id$='" + indexScore + "A-button'] span").text(reponse[indexScore + 'A']);

						if (reponse[indexScore + 'B'] == -1)
							$(".saisie a[id$='" + indexScore + "B-button'] span").text(' - ');
						else
							$(".saisie a[id$='" + indexScore + "B-button'] span").text(reponse[indexScore + 'B']);
					}
				}
				document.title = 'IsaScore ' + protocole.participant;
				headerText();
//				disableSaisie(false);
				saisieOn = true;
				$("#cotationForm").slideDown(TIME_OUT);
				$("#saveProto").attr({"disabled":"disabled"});
				$("#reloadProto").attr({"disabled":"disabled"});
				$("#closeProto").removeAttr("disabled");

				setTimeout(function () {
					alertIsa('Passation rechargée', MESSAGE_COLOR, true);
				}, TIME_OUT * 2);
			}
		}
	});

}
//**********************************************************************************************
function qAjaxTableChooseCols(command) {
	var panelCode = '';
	var chooseCols = JSON.parse(localStorage.ISAchooseCols);
	panelCode += '<div style="padding:20px">';
	panelCode += '<fieldset data-role="controlgroup">';
	panelCode += '<h3>Colonnes à afficher:</h3>';

	for (var col = 1; col < chooseCols.length; col++) {
		panelCode += '<input type="checkbox" data-mini="false" id="' + chooseCols[col].trueColName + 'panel"><label for="' + chooseCols[col].trueColName + 'panel">' + chooseCols[col].displayColName + '</label>';
	}
	panelCode += '</fieldset>';
	panelCode += '</div>';

	$("#chooseColsPanel #colsBlock").html(panelCode).trigger( "updatelayout" );
	$("#chooseColsPanel input").closest("div").trigger("create");

	for (let col = 1; col < chooseCols.length; col++) {
		if (chooseCols[col].visible) $('#' + chooseCols[col].trueColName + 'panel').prop('checked',true).checkboxradio('refresh');
	}
}
//**********************************************************************************************
function verifChooseCols() {
	var chooseCols;
	if (localStorage.ISAchooseCols) chooseCols = JSON.parse(localStorage.ISAchooseCols);
	if (chooseCols &&
    chooseCols[0].trueColName == 'participant' && chooseCols[0].displayColName == 'Participant' &&
      chooseCols[1].trueColName == 'date' && chooseCols[1].displayColName == 'Date' &&
      chooseCols[2].trueColName == 'time' && chooseCols[2].displayColName == 'Heure' &&
      chooseCols[3].trueColName == 'period' && chooseCols[3].displayColName == 'Periode' &&
      chooseCols[4].trueColName == 'ageChrono' && chooseCols[4].displayColName == 'Age' &&
      chooseCols[5].trueColName == 'ageDev' && chooseCols[5].displayColName == 'Age (dév.)' &&
      chooseCols[6].trueColName == 'sex' && chooseCols[6].displayColName == 'Genre' &&
      chooseCols[7].trueColName == 'location' && chooseCols[7].displayColName == 'Etablissement' &&
      chooseCols[8].trueColName == 'rem1' && chooseCols[8].displayColName == '#1' &&
      chooseCols[9].trueColName == 'rem2' && chooseCols[9].displayColName == '#2' &&
      chooseCols[10].trueColName == 'rem3' && chooseCols[10].displayColName == '#3' &&
      chooseCols[11].trueColName == 'rem_libre' && chooseCols[11].displayColName == 'Remarques'
	) return;
	chooseCols = [
		{trueColName:'participant', displayColName:'Participant', visible:true},
		{trueColName:'date', displayColName:'Date', visible:true},
		{trueColName:'time', displayColName:'Heure', visible:false},
		{trueColName:'period', displayColName:'Periode', visible:false},
		{trueColName:'ageChrono', displayColName:'Age', visible:false},
		{trueColName:'ageDev', displayColName:'Age (dév.)', visible:false},
		{trueColName:'sex', displayColName:'Genre', visible:false},
		{trueColName:'location', displayColName:'Etablissement', visible:false},
		{trueColName:'rem1', displayColName:'#1', visible:false},
		{trueColName:'rem2', displayColName:'#2', visible:false},
		{trueColName:'rem3', displayColName:'#3', visible:false},
		{trueColName:'rem_libre', displayColName:'Remarques', visible:false}
	];

	localStorage.ISAchooseCols = JSON.stringify(chooseCols);
}
//**********************************************************************************************
function buildChooseColsRequete() {
	var requete = "";
	var data = "";
	var chooseCols = JSON.parse(localStorage.ISAchooseCols);
	var dataIndex = 1;
	for (var col = 0; col < chooseCols.length; col++) {
		if (chooseCols[col].visible) {
			if (dataIndex++ > 1) {
				requete += ", ";
				data += ',';
			}
			requete += chooseCols[col].trueColName + " as `" + chooseCols[col].displayColName + "`";

			data += chooseCols[col].trueColName;
		}
	}
	return {'requete':requete, 'data': data};
}
//**********************************************************************************************
function bricoShowHide () {
	window.document.write( '<style>div[data-role="footer"]{visibility:hidden}@media(min-height:' + ($( window ).height() - 10) + 'px){div[data-role="footer"]{visibility:visible}}</style><style>div[data-role="header"]{visibility:hidden}@media(min-height:' + ($( window ).height() - 10) + 'px){div[data-role="header"]{visibility:visible}}</style>' );
}
//**********************************************************************************************
function isMobile() {
	mobile = ['iphone','ipad','android','blackberry','nokia','opera mini','windows mobile','windows phone','iemobile'];
		for (var i in mobile) if (navigator.userAgent.toLowerCase().indexOf(mobile[i]) != -1) return true;
		return false;
}
//**********************************************************************************************
function displayPanelActualPar(elem) {
	var row = $(elem).closest("tr").html();
	$("#actualParPanel").html("<strong style='color:steelBlue;'><table><tbody>" + row + "</tbody></table></strong><br />");
	$("#actualParPanel").find("td").remove(":first").remove(":last");
	$("#actualParPanel td").unwrap().wrap("<tr>");
}
//**********************************************************************************************
function detailMiniPanelShow() {
	if ($("#detailMiniPanel").css("display") == 'none') $("#detailMiniPanel").slideDown(TIME_OUT / 2);
	else $("#detailMiniPanel").slideUp(TIME_OUT / 2);
}
//**********************************************************************************************
function savePending() {
	if ( $(".resumPass").attr('data-isa-pending') == 'true' ) return true;
	else return false;
}
//**********************************************************************************************

//*****************************************************************************************
//*****************************************************************************************
//*************************************** MOBILE init **************************************
$(document).on("mobileinit", function() {
//	$.mobile.orientationChangeEnabled = false;  //  deprecated ?


//     BLOQUAGE BACK BUTTON
  $.mobile.hashListeningEnabled = false;
  $.mobile.pushStateEnabled = false;
	$.mobile.changePage.defaults.changeHash = false;

  $.mobile.button.prototype.options.shadow = "false";

	$.event.special.swipe.horizontalDistanceThreshold = 60;
  $.event.special.swipe.verticalDistanceThreshold = 50;
	$.event.special.swipe.durationThreshold = 1;  // 1000

//	$.mobile.textinput.prototype.options.height = "152px";
//  $.mobile.phonegapNavigationEnabled = "true";
	$.mobile.defaultPageTransition = 'none';
	$.mobile.popup.prototype.options.transition = "pop";

//	if (protocole.version.indexOf('ndroid') == -1) $.mobile.popup.prototype.options.overlayTheme = "b";

//  $.mobile.page.prototype.options.domCache = true;
//  $.mobile.button.prototype.options.corners = "false";
});
//*****************************************************************************************
//*************************************** PAGE INIT **************************************

$(document).on( "click", "button, a[data-role='button'], input[type='button']", function () {
	this.blur();
});

$(document).on( "toolbarcreate", function( event, ui ) {
	$(event.target).toolbar( "option", "transition", "slide" );
  // $( event.target ).toolbar({ updatePagePadding: true });

	$(event.target).find("a[href^='#phase'], a[href='#record']").addClass("cotationTitle");
	$(event.target).find("a[href='#accueil']").addClass("accueilTitle");
	$(event.target).find("a[href='#query']").addClass("passationsTitle");

																// hide-show toolbar on input
//	$( event.target ).toolbar({ hideDuringFocus: "input, textarea, select" });
});

$(document).on("pagecreate", function() {
  $(".ui-input-text")
            .removeClass("ui-corner-all")
            .removeClass("ui-shadow-inset");
  $("div .ui-btn").removeClass("ui-shadow");
  $("div[data-role='popup']").removeClass("ui-overlay-shadow");
	$("div[data-role='footer'], div[data-role='header']").attr({"data-position":"fixed", "data-tap-toggle":"false"}).css({"border-top-color":"#EEEEEE", "border-bottom-color":"#EEEEEE"});
});
//----------------------------------------------------------     #accueil
$(document).on("pagecreate", "#accueil", function() {

	$("#accueil code").text("IsaScore-" + version);

	$("#accueil").on("swiperight", function () {
		$("body").pagecontainer("change", "#consigne", {transition: "slide", reverse: true});
    });
	$("#accueil").on("swipeleft", function () {
		$("body").pagecontainer("change", "#query", {transition: "slide"});
    });
/*
	$(document).on("scrollstop", function() {  // pagebeforeshow  // show footer on scroll
		var elem = $(document.activeElement);
		if (!((elem.is("input")) || (elem.is("textarea")))) {
			$.mobile.activePage.find("div[data-role='footer'], div[data-role='header']").show();
		}
	});
*/
});
//---------------------------------------------------    #detailDialog
$(document).on("pagecreate", "#detailDialog", function(event) {

	$("#detailReloadButton").on("click", function(event) {
		if (reloadId == $(this).closest("div[role='main']").find("#detailText").attr("data-isa-id")) {
			$("body").pagecontainer("change", "#record", {transition: 'flip'});
		}
//		else if (reloadId > 0)
//			alertIsa('Fermer d\'abord la cotation en cours', 'crimson', false);
		else {
      closeProto();
			reloadPassation($(this).closest("div[role='main']").find("#detailText").attr("data-isa-id"), $(this).closest("div[role='main']").find("#detailText").attr("data-isa-participant"));  // role sans data- (????)
		  $("body").pagecontainer("change", "#record", {transition: 'flip'});
      // refreshUserParticipants();
		}
	});

	$("#detailDuplicateButton").on("click", function(event) {
		doClickActionPopup('duplicateObservation', $("#detailDialog").attr("data-isa-id"));
	});

	$("#detailDeleteButton").on("click", function(event) {
		doClickActionPopup('deleteObservation', $("#detailDialog").attr("data-isa-id"));
	});

	$("#detailDialog").on("swiperight click", "#detailText", function() {
		$("body").pagecontainer("change", "#query", {transition: 'slide', reverse: true});
	});

	$("#detailDialog").on("click", "#detailText", function() {
		$("#detailMiniPanel").slideUp(TIME_OUT / 2);
	});
});

//---------
$(document).on("pagebeforeshow", "#detailDialog", function() {
	$("#detailMiniPanel").hide();
});
//---------------------------------------------------    #record
$(document).on("pagecreate", "#record", function() {
//		disableSaisie(true);
		$("#cotationForm").fadeOut(TIME_OUT);
		$(".radio div[class*='ui-controlgroup-controls']").css({"margin-left":"20px"});

		$("#reloadProto").on("click", function () {
			if ( false )  // (reloadId > 0)
				alertIsa('Fermer d\'abord la cotation en cours', 'crimson', false);
			else {
        closeProto();
				openUserParticipants();
				$("body").pagecontainer("change", "#query", {transition: 'flip'});
				setTimeout(function () {
					alertIsa('Choisir une passation', MESSAGE_COLOR, true);
				}, TIME_OUT * 2);
			}
		});

});
//---------------------------------------------------    #consigne
$(document).on("pagecreate", "#consigne", function() {
		$("#consigne").on("swipeleft", function () {
			$("body").pagecontainer("change", "#accueil", {transition: "slide"});
        });
});
//---------------------------------------------------    #query
$(document).on("pagecreate", "#query", function() {

	$(".mail").on("change", function() {
		storeMail($(this).val());
	});

	$("#chooseActionsPanel").find("#deletePar" ).on("click", function () {
		doClickActionPopup('deleteObservation', $("#chooseActionsPanel").attr("data-isa-id"));
	});

	$("#chooseActionsPanel").find("#duplicatePar").on("click", function () {
		doClickActionPopup('duplicateObservation', $("#chooseActionsPanel").attr("data-isa-id"));
	});

	$("#chooseActionsPanel").find("#reloadPar").on("click", function () {
		if(reloadId == $("#chooseActionsPanel").attr("data-isa-id")) {
			$("body").pagecontainer("change", "#record", {transition: 'flip'});
		}
//		else if (reloadId > 0)
//			alertIsa('Fermer d\'abord la cotation en cours', 'crimson', false);
		else {
      closeProto();
			reloadPassation($("#chooseActionsPanel").attr("data-isa-id"));
			$("body").pagecontainer("change", "#record", {transition: 'flip'});
		}
	});

	//---------- colNamesPanel close
	$( "#chooseColsPanel" ).on( "panelbeforeclose", function( event, ui ) {
		var avant = '', apres = '';
		var chooseCols = JSON.parse(localStorage.ISAchooseCols);
		for (var col = 1; col < chooseCols.length; col++) {
			avant += chooseCols[col].visible;
			var colName =  chooseCols[col].trueColName;
			if ($( "#chooseColsPanel label[for='" + colName + "panel']" ).hasClass( "ui-checkbox-on" ))
				 chooseCols[col].visible = true;
			else chooseCols[col].visible = false;
			apres += chooseCols[col].visible;
		}
		localStorage.ISAchooseCols = JSON.stringify(chooseCols);
		if (avant != apres) refreshUserParticipants();
	});

	// ----------- choosePanel
	$( "#chooseActionsPanel, #chooseColsPanel" ).on( "panelbeforeopen", function() {
		$(".mail").val(storedMail());
		$("#query div[data-role='footer']").hide();
	});

	$( "#chooseActionsPanel, #chooseColsPanel" ).on( "panelbeforeclose", function() {
		$(".mail").val(storedMail());
		$("#query div[data-role='footer']").show();

	});


// ??????????????????????????????????????????????????
	$("#query").on("swiperight", function () {
		$("body").pagecontainer("change", "#accueil", {transition: "slide", reverse: true});
    });

					//------------  qAjaxTables
    $("#qConnections").on("click", function() {
        qAjaxTable('Connections', true, '');
	});
    $("#qUserParticipants").on("click", function() {
        qAjaxTable('UserParticipants', true, '');
	});
    $("#qResum").on("click", function() {
        qAjaxTable('Resum', true, '');
	});
    $("#qUsers").on("click", function() {
        qAjaxTable('Users', true, '');
	});
    $("#qLocations").on("click", function() {
        qAjaxTable('Locations', true, '');
	});

});

//--------------------------------------------------- 		   #phase
$(document).on("pagecreate","div[id^='phase']", function() {
    $("div[id^='phase'] div[class='ui-block-c'] div, div[id^='phase'] div[class='ui-block-d'] div")
            .css({"border-top":"2px solid rgba(0,80, 128, 0.5)", "border-left":"2px solid rgba(0,80, 128, 0.5)"});
});

$(document).on("pagebeforeshow","div[id^='phase']", function() {
	onOfSaisie(this);
});
//...................


$(window).on("orientationchange resize", function() {
	$("div[id^='phase'] .phaseLine1").css({"width":window.innerWidth - PHASE_LINE1 + "px"});
	$("div[id^='phase'] .phaseLine2").css({"width":window.innerWidth - PHASE_LINE2 + "px"});
//	$("div[data-role='footer']").show();   //  .removeClass("ui-fixed-hidden")
	$.mobile.activePage.find("div[data-role='footer'], div[data-role='header']").show();
});


//------------------------------------------------------
//------------------------------------------------------

$(document).on("pagecreate","#record, #query, #detailDialog", function() {
    $("input[type='text'], input[type='password'], input[type='email']").css({"border-width":"0px", "border-left":"1px solid rgba(0,80, 128, 0.7)"});
    $("#record textarea").css({"border-top":"1px solid rgba(0,80, 128, 0.7)"});
    $("#detailDialog .slide-button, #query .slide-button, #query a[data-role='button'], #record a[data-role='button']").css({"border-bottom":"1px solid white", "border-top":"1px solid crimson", "border-right":"0px solid white", "border-left":"0px solid white"});
});

$(document).on("pagecreate","#record, #query, #accueil", function() {
    $(".alertPopup, .confirmPopup").css({"background":POPUP_BG, "border":"5px solid white", "max-width":"30em"});
	$(".alertPopup a").css({"color":MESSAGE_COLOR});
	$(".alertPopup").css({'cursor':'pointer'}).on("click", function() {
		$(this).popup("close");
	});

    $(".confirmOK").css({"color":POPUP_OK});
    $(".confirmCancel").css({"color":POPUP_CANCEL});
    $(".alertPopup p, .confirmPopup p").css({"font-size":"large", "margin":"32px", "text-align":"center"});

});
//*****************************************************************************************
$(document).on("pagebeforeshow", function() {  //
    document.title = 'IsaScore ' + protocole.participant;
	$(".loader").hide();
});



/*
$(document).on('pagebeforechange', function(e){
  var isScrollable = !$('body').hasClass('preventNativeScroll');
  if (isScrollable){
    $('body').removeClass('preventNativeScroll');
  } else {
    $('body').addClass('preventNativeScroll');
  }
});*/
//*****************************************************************************************
//*****************************************************************************************
//*****************************************************************************************
//*****************************************************************************************
//*****************************************************************************************
//****************************************  READY  **********************************/
$(document).ready(function() {

//	document.body.style.fontFamily = "Comic Sans MS, sans-serif";
	initIsa();

//------------------------------------------------------------
//     BLOQUAGE BACK BUTTON

	document.addEventListener('backbutton', function(event) {
		event.stopPropagation();
		event.preventDefault();
		return false;
	}, false);

	$(document).on('dblclick', function() {
		event.stopPropagation();
		event.preventDefault();
		return false;
	});

//-------------------------------------------------------------------------- bricole widget

															//  montrer/cacher footer sur clic img
/*
    $("img").on("click", function() {
		if ($(this).closest("#accueil").length > 0) return;
		var footHead = $(this).closest("div[data-role='page']").find("div[data-role='footer'], div[data-role='header']");
		if (footHead.css("display") == "block") {
			footHead.hide();
		}
		else footHead.show();
	});
*/
  $("div[id^='phase'] div[role='main'] a")
            .attr({"data-transition":"slide", "data-icon":"info", "data-iconpos":"notext"})
            .css("background-color","#FFF");
//...................
  $("div[id^='phase'] select")
            .attr({"data-native-menu":"false", "data-iconshadow":"false", "data-shadow":"false", "data-icon":"false"});
//...................
	$("div[id^='phase'] .phaseLine1").css({"width":window.innerWidth - PHASE_LINE1 + "px"});
	$("div[id^='phase'] .phaseLine2").css({"width":window.innerWidth - PHASE_LINE2 + "px"});

//...................
  $("a[data-role='button'], button").attr({"data-iconshadow":"false", "data-shadow":"false"});
//...................
  $(".ui-input-text").attr({"data-corners":"false"});

//...................

																// hide-show toolbar on input
/*
	$("input, textarea").focusin(function() {
		if (window.screen.width < 1200)
			$.mobile.activePage.find("div[data-role='footer'], div[data-role='header']").hide();
    });
	$("input, textarea").focusout(function() {
		$.mobile.activePage.find("div[data-role='footer'], div[data-role='header']").show();
    });
*/



//...................
  $("input[type='text'], input[type='password'], input[type='email'], textarea").css(({"color":"steelBlue"}));
//...................
  $(document).ajaxStart(function () {
		if (!backgroundSaveOn) $(".loader").show();
			backgroundSaveOn = false;
	}).ajaxStop(function () { $(".loader").hide(); });
//...................
//***************************************************************************************
//---------------------------------------------------------- init sauvegarde
  $("#participant").on("change", function () {
	if (protocole.observateur == 'superisa') {
		$(this).val("");
		return;
	}
	var trimed = $.trim($(this).val());
	if (trimed.match(/^[a-zA-Z](\w|_|-)+$/)) {
		protocole.participant = trimed;
		document.title = 'IsaScore ' + protocole.participant;
		headerText();
	}

	else {
		if ($.trim($(this).val())) alertIsa('Nom de participant invalide!', 'crimson', false);
		protocole.participant = '';
		document.title = 'IsaScore';
		headerText();
	}
  });

  $("#period").on("change", function () {
      protocole.period = $.trim($(this).val());
  });
  $("#ageChrono").on("change", function () {
      protocole.ageChrono = $.trim($(this).val());
  });
  $("#ageDev").on("change", function () {
      protocole.ageDev = $.trim($(this).val());
  });
  $("#record input[name='sex']").on("change", function () {
      protocole.sex = $.trim($(this).val());
  });



  $("#location").on("change", function () {
      protocole.location = $.trim($(this).val());
  });
  $("#rem1").on("change", function () {
      protocole.rem1 = $.trim($(this).val());
  });
  $("#rem2").on("change", function () {
      protocole.rem2 = $.trim($(this).val());
  });
  $("#rem3").on("change", function () {
      protocole.rem3 = $.trim($(this).val());
  });
  $("#rem_libre").on("change", function () {
      protocole.rem_libre = $.trim($(this).val());
  });

  $(".saisie textarea").on("change", function () {
      protocole[this.id] = $.trim($(this).val());
  });


	$(".sauvegarde, .saisie select, .saisie textarea").on("change", function () {
		if (protocole.observateur == 'superisa') return;
		backgroundSave();
	});


	$(".saisie select").on("change", function () {
        protocole[this.id] = $(this).val();
	});


//*********************************************************************************
//---------------------------------------------------------- identification
    $("#popupLogin #login").on("click", function(event) {

        var username = $.trim($("#observateur").val());
        var password = $.trim($("#password").val());
		if (!password || (password.match(/^.{8,15}$/) && !password.match(/\s/))) {
			$.ajax({
				url: 'isaLogin.php',
				data: {username: username, password: password},
				complete: function(xhr, result) {
					$(".loader").hide();
					if (result != 'success') {
						alertIsa('Pas de réseau! Passation NON enregistrée.\n', 'crimson', false);
					}
					else {
						var reponse = xhr.responseText;
						if (reponse != 'OK') {
							alertIsa('Observateur ou mot de passe erroné!', 'crimson', false);

							$("#observateur").val("");
							$("#password").val("");
							return;
						}
						else {
							$("#popupLogin").hide();
							$("#changeUser").show();
							observateur = protocole.observateur = username;
							if (observateur == 'superisa') {
								$("#query #qrConnections").show();
								$("#query #qUsers").css({"color":"crimson"});
								$("#query #qUserParticipants").css({"color":"crimson"}).text("Toutes les passations");
							}
							else {
								$("#saveProto").removeAttr("disabled");
								$("#reloadProto").removeAttr("disabled");
								$("#query #qUserParticipants").text('Passations de ' + observateur).attr('title', 'Afficher les passations de ' + observateur);
							}
							headerText();
							$.mobile.silentScroll(0);
							setTimeout(function () {
								alertIsa('Bonjour ' + username, MESSAGE_COLOR, true);
							}, TIME_OUT / 8);
							openUserParticipants();
							$("#query #qrUserParticipants").show();
						}
					}
				}
			});
		}
		else {
			alertIsa('Mot de passe invalide!\n(minimum 8 caractères)', 'crimson', false);
			$("#password").val("");
		}
    });
//****************************************************************************************
//------------------------------------------------------------ (sauvegarde old)  NOUVELLE PASSATION
    $("#saveProto").on("click", function(event) {

		protocole.reloadId = 0;

        $.ajax({
            url: 'saveProto.php',
			type: 'POST',
            data: protocole,  // JSON.stringify(protocole),
			timeout: TIME_OUT * 10,
            complete: function(xhr, result) {
				$(".loader").hide();
                if (result != 'success') {
                    alertIsa('Pas de réseau! Passation NON crée', 'crimson', false);
                }
				else {
                    var reponse = xhr.responseText;
                    if (reponse === 0) {
                        alertIsa('Erreur réseau! Passation NON crée', 'crimson', false);
                    }
                    else {
                        initProto();
						reloadId = reponse;
						saisieOn = true;
						$("#reloadProto").attr({"disabled":"disabled"});
						$("#saveProto").attr({"disabled":"disabled"});
						$("#closeProto").removeAttr("disabled");
                        alertIsa('Près pour la cotation d\'une nouvelle passation', MESSAGE_COLOR, true);
						$("#cotationForm").slideDown(TIME_OUT);
//						setTimeout(function () {
//							$.mobile.silentScroll(120);
//						}, TIME_OUT * 2);
                        headerText();
						refreshUserParticipants();
                    }
                }
            }
        });
    });
//*****************************************************************************************

});    // FIN .READY
																		// FIN .READY
//*****************************************************************************************
//*****************************************************************************************
