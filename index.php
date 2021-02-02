<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/locale/fi.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.fi.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/jquery.scrollto@2.1.2/jquery.scrollTo.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-datepicker.css">
<link rel="icon" type="image/png" href="images/favicon.ico" sizes="64x64">
<title>Ajanvaraus</title>
</head>
<body>
<div class="container">
<div class="backgroundarea">
	<div class="row">
		<div class="col-lg-12 form-group"><br>
			<img alt="ajavalogo" class="logo" src="images/ajava_logo.png"><br><br>
			<select name="kategoria" id="kategoria" class="form-control">
				<?php include("connect.php");
				//Haetaan tietokannasta kategoriat. Jos niitä ei ole, ilmoitetaan siitä.
					$sql = $conn->prepare("SELECT nimi, id FROM kategoria ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option selected="selected" value="poistettu" disabled >Valitse kategoria</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					else {
						echo "<option value='noresults'>Ei kategorioita</option>";
					}
				?>
			</select>
			<select name="palvelu" id="palvelu" disabled class="form-control">
			</select>
			<select name="tyontekija" id="tyontekija" disabled class="form-control">
			</select>
			<select name="toimipiste" id="toimipiste" disabled class="form-control">
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div id="datepicker" hidden>
			</div>
			<div id="valittavatAjat" hidden>
			<h2 ><b>Valittavissa olevat ajat:</b></h2>
			<div class="buttons">
			</div>
			</div>
			<div class="hakuerror" id="noresults" hidden>
			<hr>
			<h2>Valituilla hakuehdoilla ei löytynyt tuloksia.</h2>
			</div>
			<div class="hakuerror" id="errorfile" hidden>
			<hr>
			<h2>Yritä myöhemmin uudelleen.</h2>
			</div>

			<script>
			/*Funktio, jolla määritetään bootstrap datepicker -kalenteri annetun päivämääräarrayn perusteella.
			  Funktio tarkistaa jokaisen päivän kohdalla, löytyykö se päivämääräarraysta. Jos sitä ei löydy, niin
			  sitä ei laiteta saatavaksi. Samalla asetetaan päivämääräformaatti ja kieleksi suomi.*/
			function lataaKalenteri(result) {
				var datesEnabled = result;
				$('#datepicker').datepicker({
					format: "yyyy-mm-dd",
					language: "fi",
					minDate: new Date(),
					beforeShowDay: function (date) {
					  var date = moment(date).format('YYYY-MM-DD');
					  if(datesEnabled.indexOf(date) != -1) {
						return true;
					  }
					  else {	  
						return false;
					  }
					}
				});
				//Kun kalenteri on ladattu, pistetään se näkyviin.
				$('#datepicker').prop('hidden', false);
			}
			//Funktio, jolla haetaan kyseisenä päivänä saatavilla olevat ajat, kun päivää klikataan kalenterista.
			$(function() {
				$('#datepicker').on('changeDate', function() {
					tulostaAjat();
				});
			});
			</script>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
			  <div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Lisätiedot</h4>
				  </div>
				  <div class="modal-body">
				  <h3 id="kategoriaModal"></h3>
				  <h3 id="palveluModal"></h3>
				  <h3 id="tyontekijaModal"></h3>
				  <h3 id="toimipisteModal"></h3>
				  <h3 id="paivamaaraModal"></h3>
				  <h3 id="aikaModal"></h3>
				  <script>
				  /*Funktio, jolla alustetaan modal. Sille tuodaan työntekijän numero, nimi ja aika listana klikatun ajan funktiona.
				    Tämän lisäksi se ottaa muut tiedot valintabokseista ja muuttaa päivämäärät haluttuun muotoon*/
					function lataaModal(tietoArray)
					{
						var tyontekijaNumero = tietoArray[0];
						var tyontekijaNimi = tietoArray[1];
						var aika = tietoArray[2];
						var saatavillaID = tietoArray[3];
						var kategoriaModal = $("#kategoria option:selected").text();
						var palveluModal = $("#palvelu option:selected").text();
						var tyontekijaModal = tyontekijaNimi;
						var toimipisteModal = $("#toimipiste option:selected").text();
						var paivamaaraAlku = $("#datepicker").datepicker("getDate");
						var paivamaaraModal = moment(paivamaaraAlku).format('DD.MM.YYYY');
						var kategoriaValue = $("#kategoria").val();
						var palveluValue = $("#palvelu").val();
						var tyontekijanumero = tyontekijanumero;
						var toimipisteValue = $("#toimipiste").val();
						var paivamaaraValue = moment(paivamaaraAlku).format('YYYY-MM-DD');
						/*Asetetaan edellä määritetyt muuttujat html-elementteihin, jotta käyttäjä näkee ne. Kuljetetaan myös joitain
						  elementtejä piilossa olevilla inputeilla lomakkeenlähettäjälle*/ 
						$("#kategoriaModal").html("Kategoria: " + kategoriaModal);
						$("#palveluModal").html("Palvelu: " + palveluModal);
						$("#tyontekijaModal").html("Työntekijä: " + tyontekijaModal);
						$("#toimipisteModal").html("Toimipiste: " + toimipisteModal);
						$("#paivamaaraModal").html("Päivämäärä: " + paivamaaraModal);
						$("#aikaModal").html("Kellonaika: " + aika);
						$("#kategoriaValue").val(kategoriaValue);
						$("#palveluValue").val(palveluValue);
						$("#tyontekijaValue").val(tyontekijaNumero);
						$("#toimipisteValue").val(toimipisteValue);
						$("#paivamaaraValue").val(paivamaaraValue);
						$("#kategoriaText").val(kategoriaModal);
						$("#palveluText").val(palveluModal);
						$("#tyontekijaText").val(tyontekijaModal);
						$("#toimipisteText").val(toimipisteModal);
						$("#paivamaaraText").val(paivamaaraModal);
						$("#aikaValue").val(aika);
						$("#saatavillaID").val(saatavillaID);
					}
				  </script>
				  <hr>
				  <div id="formerror" hidden>
				  Tapahtui virhe. Yritä myöhemmin uudelleen.
				  </div><br>
				  <form id="lisatietolomake" method="post">
				  <label for="etunimi" class="modallabel">Etunimi *</label>
				  <input type="text" id="etunimi" class="modalform" placeholder="Etunimi" name="enimi"><div class="etunimidiv"></div>
				  <label for="sukunimi" class="modallabel">Sukunimi *</label>
				  <input type="text" id="sukunimi" class="modalform" placeholder="Sukunimi" name="snimi"><div class="sukunimidiv"></div>
				  <label class="modallabel">Sähköpostiosoite</label>
				  <label for="muistutus" class="muistutustext"><input type="checkbox" id="muistutus" value="valittu" name="muistutuslaatikko"> Muistutus sähköpostilla</label>
				  <input type="text" class="modalform" placeholder="Sähköpostiosoite" id="sposti" name="sposti" disabled><div class="spostidiv"></div>
				  <label for="puhnum" class="modallabel">Puhelinnumero *</label>
				  <input type="tel" id="puhnum" class="modalform" placeholder="Puhelinnumero" name="puhnum" pattern="[\d+()\-\s]{5,25}"><div class="puhnumdiv"></div>
				  <input type="text" id="kategoriaValue" name="kategoriaValue" hidden>
				  <input type="text" id="palveluValue" name="palveluValue" hidden>
				  <input type="text" id="tyontekijaValue" name="tyontekijaValue" hidden>
				  <input type="text" id="toimipisteValue" name="toimipisteValue" hidden>
				  <input type="text" id="paivamaaraValue" name="paivamaaraValue" hidden>
				  <input type="text" id="kategoriaText" name="kategoriaText" hidden>
				  <input type="text" id="palveluText" name="palveluText" hidden>
				  <input type="text" id="tyontekijaText" name="tyontekijaText" hidden>
				  <input type="text" id="toimipisteText" name="toimipisteText" hidden>
				  <input type="text" id="paivamaaraText" name="paivamaaraText" hidden>
				  <input type="text" id="aikaValue" name="aikaValue" hidden>
				  <input type="text" id="saatavillaID" name="saatavillaID" hidden>
				  <label for="lisatiedot" class="modallabel">Lisätietoja</label>
				  <textarea class="modalform" id="lisatiedot" rows="5" placeholder="Lisätietoja" name="lisatiedot"></textarea><br>
				  <input type="submit" value="Tee varaus" class="btn btn-success submitnappi"><br><p class="modallabel"><i>Antamasi tiedot tallentuvat tietokantaan ja poistuvat sieltä varauksen jälkeen.</i></p>
				  </form>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
				  </div>
				  <script>
				  /*Jos sähköpostimuistutusta klikataan ja valintaboksi on tyhjä, sallitaan sähköpostikentttään kirjoittaminen.
				    Jos valintaboksissa taas on rasti ja sitä klikataan, tyhjennetään kenttä ja estetään siihen kirjoittaminen. */
					$(function() {
						$('.muistutustext').click(function() {
							if ($('#muistutus').is(':checked')) {
								$('#sposti').prop('disabled', false);
							}
							else {
								$('#sposti').prop('disabled', true);
								$('#sposti').html("");
							}
						});
					});
					/*Funktio, jolla tarkistetaan lisätietolomakkeen validius. Jos joku vaadituista kentistä on tyhjänä,
					  näytetään sen alapuolella virheilmoitus.*/
					$(function() {
						$('#lisatietolomake').validate({
						  rules: {
							  enimi: "required",
							  snimi: "required",
							  puhnum: "required",
							  sposti:{
								  required: "#muistutus:checked",
								  email: true
							  },
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "enimi") {
								error.appendTo(".etunimidiv");
							  }
							  else if (element.attr("name") == "snimi") {
								error.appendTo(".sukunimidiv");
							  }
							  else if (element.attr("name") == "puhnum") {
								error.appendTo(".puhnumdiv");
							  }
							  else if (element.attr("name") == "sposti") {
								error.appendTo(".spostidiv");
							  }
						  },
						  messages: {
							  enimi: "Tietoja puuttuu",
							  snimi: "Tietoja puuttuu",
							  puhnum: "Tietoja puuttuu",
							  sposti: "Tietoja puuttuu"
						  },
						  //Jos kenttien validointi menee läpi, viedään lomakkeen tiedot formsender.php-tiedostolle ajaxilla.
						  submitHandler: function(form) {
							$.ajax({
								type: "POST",
								url: "formsender.php",
								data: $('#lisatietolomake').serialize(),
								success: function(result) 
								{
									//Jos tiedosto palauttaa onnistumisen, kerrotaan käyttäjälle varauksen onnistumisesta.
									if (result == "success") {
										window.location.href = 'varausOnnistui.html';
									}
									//Muutoin näytetään virheilmoitus.
									else {
										console.log(result);
										$('#formerror').prop('hidden', false);
									}
								},
								//Jos kyselyn suoritus ei onnistu, näytetään virheilmoitus
								error: function(result) 
								{
									$('#formerror').prop('hidden', false);
									console.log(result);
								}
							});
						  }
						});
					});
				  </script>
				</div>
			  </div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
//Kun kategoria valitaan, viedään sen arvo ajaxilla erilliseen tiedostoon.
$(function() {
	$("#kategoria").change(function() {
	var kategoria = $('#kategoria').val();
		$.ajax ({
			type: "POST",
			url: "ajax_haepalvelu.php",
			data: {kategoria: kategoria},
			cache: false,
			success: function(data) {
				//Jos tiedosto palauttaa onnistumisen, viedään tulos palveluselectiin ja tyhjennetään seuraavat valintaboksit sekä piilotetaan errorit ja ajat.
				$('#palvelu').html(data);
				$('#tyontekija').prop('disabled', true);
				$('#toimipiste').prop('disabled', true);
				$('#tyontekija').html("");
				$('#toimipiste').html("");
				$('#noresults').prop('hidden', true);
				$('#datepicker').prop('hidden', true);
				$('#valittavatAjat').prop('hidden', true);
				$('.buttons').prop('hidden', true);
				$('#errorfile').prop('hidden', true);
			},
			//Jos tiedosto palauttaa virheen, näytetään virheilmoitus
			error: function(data) {
				$('#errorfile').prop('hidden', false);
			}
		});

	});
});
//Kun palvelu valitaan, viedään sen ja kategorian arvo ajaxilla erilliseen tiedostoon.
$(function() {
	$("#palvelu").change(function() {
	var kategoria = $('#kategoria').val();
	var palvelu = $('#palvelu').val();
		$.ajax ({
			type: "POST",
			url: "ajax_haetyontekija.php",
			data: {kategoria: kategoria, palvelu: palvelu},
			cache: false,
			success: function(data) {
				//Jos tiedosto palauttaa onnistumisen, viedään se tyontekijaselectiin ja tyhjennetään seuraavat valintaboksit sekä piilotetaan errorit ja ajat.
				$('#tyontekija').html(data);
				$('#toimipiste').prop('disabled', true);
				$('#toimipiste').html("");
				$('#noresults').prop('hidden', true);
				$('#datepicker').prop('hidden', true);
				$('#valittavatAjat').prop('hidden', true);
				$('.buttons').prop('hidden', true);
				$('#errorfile').prop('hidden', true);
			},
			//Jos tiedosto palauttaa virheen, näytetään virheilmoitus.
			error: function(data) {
				$('#errorfile').prop('hidden', false);
			}
		});

	});
});
$(function() {
//Kun työntekijä valitaan, viedään sen, kategorian ja palvelun arvo erilliseen tiedostoon.
	$("#tyontekija").change(function() {
	var kategoria = $('#kategoria').val();
	var palvelu = $('#palvelu').val();
	var tyontekija = $('#tyontekija').val();
		$.ajax ({
			type: "POST",
			url: "ajax_haetoimipiste.php",
			data: {kategoria: kategoria, palvelu: palvelu, tyontekija: tyontekija},
			cache: false,
			success: function(data) {
				//Jos tiedosto palauttaa onnistumisen, viedään tulos toimipisteselectiin ja piilotetaan errorit sekä ajat.
				$('#toimipiste').html(data);
				$('#noresults').prop('hidden', true);
				$('#datepicker').prop('hidden', true);
				$('#valittavatAjat').prop('hidden', true);
				$('.buttons').prop('hidden', true);
				$('#errorfile').prop('hidden', true);
			},
			//Jos tiedosto palauttaa virheen, näytetään ilmoitus siitä.
			error: function(data) {
				$('#errorfile').prop('hidden', false);
			}
		});

	});
});
$(function() {
//Kun toimipiste valitaan, viedään sen, työntekijän, palvelun ja kategorian arvo erilliseen tiedostoon.
	$("#toimipiste").change(function() {
	var kategoria = $('#kategoria').val();
	var palvelu = $('#palvelu').val();
	var tyontekija = $('#tyontekija').val();
	var toimipiste = $('#toimipiste').val();
		$.ajax ({
			type: "POST",
			url: "ajax_haepaivat.php",
			data: {kategoria: kategoria, palvelu: palvelu, tyontekija: tyontekija, toimipiste: toimipiste},
			cache: false,
			success: function(result) 
			{
				//Jos tiedosto ei palauta päiviä, näytetään virheilmoitus ja piilotetaan turhat jutut.
				if (result == "nodates")
				{
					$('#noresults').prop('hidden', false);
					$('#datepicker').prop('hidden', true);
					$('#valittavatAjat').prop('hidden', true);
					$('.buttons').prop('hidden', true);
					console.log(result);
					
				}
				/*Jos tiedosto palauttaa päiviä, viedään kyseiset päivät kalenterille ja tuhotaan edellinen kalenteri, jos sellainen on luotu.
				  Lisäksi piilotetaan errorit ym. turhat.*/
				else if (result != "error")
				{
					$("#datepicker").datepicker('destroy');lataaKalenteri(result);
					$('#errorfile').prop('hidden', true);
					$('#noresults').prop('hidden', true);
					$('#valittavatAjat').prop('hidden', true);
					$('.buttons').prop('hidden', true);
					$('#errorfile').prop('hidden', true);
				}
			}
		});

	});
});
/*Funktio, jolla tulostetaaan valitulla päivällä löytyneet ajat ajaxin avulla. 
  Ajat haetaan erillisestä tiedostosta, jolle viedään valittu päivä sekä toimipisteen, työntekijän, palvelun ja kategorian
  arvot.*/
function tulostaAjat() {
	var kategoria = $('#kategoria').val();
	var palvelu = $('#palvelu').val();
	var tyontekija = $('#tyontekija').val();
	var toimipiste = $('#toimipiste').val();
	var paivamaaraAlku = $("#datepicker").datepicker("getDate");
	var valittuPaiva = moment(paivamaaraAlku).format('YYYY-MM-DD');
		$.ajax ({
			type: "POST",
			url: "ajax_ajat.php",
			data: {valittuPaiva: valittuPaiva, kategoria: kategoria, palvelu: palvelu, tyontekija: tyontekija, toimipiste: toimipiste},
			success: function(result) {
				//Jos tuloksia ei löydy, ilmoitetaan siitä errorilla ja piilotetaan turhat jutut.
				if (result == "noresults")
				{
					console.log(result);
					$('.hakuerror').prop('hidden', false);
					$('#valittavatAjat').prop('hidden', true);
					$('.buttons').prop('hidden', true);
					
				}
				//Jos jokin meni vikaan mutta tiedoston kysely onnistui, ilmoitetaan siitäkin errorilla ja piilotetaan turhat jutut.
				else if (result == "errorfile") {
					console.log(result);
					$('#errorfile').prop('hidden', false);
					$('#valittavatAjat').prop('hidden', true);
					$('.buttons').prop('hidden', true);
				}
				//Muutoin tulostetaan ajat nappeina, scrollataan niihin ja piilotetaan errorit.
				else {
					console.log(result);
					$('.buttons').prop('hidden', false);
					$('.buttons').html(result);
					$('body').scrollTo('#valittavatAjat', 500);
					$('#noresults').prop('hidden', true);
					$('#errorfile').prop('hidden', true);
				}
			},
			//Jos kysely ei onnistunut, näytetään virheilmoitus.
			error: function(result) {
				console.log(result);
				$('#errorfile').prop('hidden', false);
				$('#valittavatAjat').prop('hidden', true);
				$('.buttons').prop('hidden', true);
			}
		});
};
//Jos kategoriaa vaihdetaan, sallitaan palvelun vaihtaminen.
$(function() {
	$('#kategoria').change(function () {
	  if ($('#kategoria').val() != "poistettu") {
		  var palvelu = $('#palvelu');
		  palvelu.prop("disabled", false);
		}
	});
});
//Jos palvelua vaihdetaan, sallitaan työntekijän vaihtaminen.
$(function() {
	$('#palvelu').change(function () {
	  if ($('#palvelu').val() != "poistettu") {
		  var tyontekija = $('#tyontekija');
		  tyontekija.prop("disabled", false);
		}
	});
});
//Jos työntekijää vaihdetaan, sallitaan toimipisteen vaihtaminen.
$(function() {
	$('#tyontekija').change(function () {
	  if ($('#tyontekija').val() != "poistettu") {
		  var toimipiste = $('#toimipiste');
		  toimipiste.prop("disabled", false);
		}
	});
});
</script>
</body>
</html>