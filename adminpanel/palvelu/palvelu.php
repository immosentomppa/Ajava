			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Kaikki palvelut</h2>
				<label>Valitse kategoria, jonka palvelut näytetään</label>
				<select class="form-control" id="palvelukategoria">
				<?php
				//Haetaan kaikki palvelut valitun kategorian perusteella.
				include('../../connect.php');
				$sql = $conn->prepare("SELECT id as kategoriaid, nimi as kategorianimi FROM kategoria");
				$sql->execute();
				if ($sql->rowCount() > 0) {
					echo '<option value="poistettu" disabled selected>Valitse kategoria</option>';
					while ($rows = $sql->fetch()) {
						echo '<option value="'.$rows['kategoriaid'].'">' . $rows['kategorianimi'] . '</option>';
					}					
				}
				//Jos kategorioita ei ole, tiedotetaan siitä.
				else {
					echo "<option value='noresults' disabled selected>Ei kategorioita</option>";
				}
				//Lopuksi tuhotaan tietokantayhteys.
				$conn = null;
				?>
				</select><br>
				<div class="eipalveluita text-left" hidden>
				<label id="noservices">Ei palveluita kategoriassa.</label>
				</div>
				<div class="tryagain text-left" hidden>
				<label id="tryagain">Yritä myöhemmin uudelleen.</label>
				</div>
				<div class="tableservices">
				</div>
				<div class="text-left lisaa_openclose"><h2 id="lisaabtn" class="text-left">Lisää palvelu</h2><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label>Valitse palvelun kohdekategoria</label>
				<select class="form-control lisaaselect" id="lisaapalvelu_kategoria" name="lisaapalvelu_kategoria">
				<?php
					//Haetaan kategoriat palvelun lisäämistä varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM kategoria ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse kategoria</option>';
						while($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos kategorioita ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei kategorioita</option>";
					}
					//Lopuksi tuhotaan tietokantayhteys.
					$conn = null;
				?>
				</select><div class="add_errorcategory"></div>
				<label>Anna palvelun nimi</label>
				<input type="text" name="lisattavapalvelu" class="form-control lisaainput palvelunnimi" placeholder="Anna palvelun nimi"><div class="add_errorservice"></div>
				<label for="palvelunkesto">Anna palvelun kesto</label>
				<select class="form-control lisaaselect palvelunkesto" name="palvelunkesto">
				<option value="poistettu" disabled selected>Valitse palvelun kesto</option>
				<option value="1800">30 minuuttia</option>
				<option value="3600">1 tunti</option>
				<option value="5400">1 tunti ja 30 minuuttia</option>
				<option value="7200">2 tuntia</option>
				<option value="9000">2 tuntia ja 30 minuuttia</option>
				<option value="10800">3 tuntia</option>
				<option value="12600">3 tuntia ja 30 minuuttia</option>
				<option value="14400">4 tuntia</option>
				<option value="16200">4 tuntia ja 30 minuuttia</option>
				<option value="18000">5 tuntia</option>
				</select><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left poista_openclose"><h2 id="poistabtn" class="text-left">Poista palvelu</h2><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<form method="post" class="poistaform">
				<label>Valitse kategoria</label>
				<select class="form-control poistaselect" name="poistapalvelu_kategoria" id="poistapalvelu_kategoria">
				<?php
					//Haetaan kategoriat palvelun poistamista varten,
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM kategoria ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse kategoria</option>';
						while($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos kategorioita ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei kategorioita</option>";						
					}
					//Lopuksi tuhotaan tietokantayhteys.
					$conn = null;
				?>
				</select><div class="remove_errorcategory"></div>
				<label>Valitse poistettava palvelu</label>
				<select name="poistettavapalvelu" class="form-control poistaselect poistapalvelu_tulokset" disabled>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				<script>
				$(function() {
					//Jos lisää palvelu -nappia painetaan, piilotetaan palvelun poisto ja avataan se.
					$(".lisaa_openclose" ).click(function() {
						if ($('.lisaa_arrow').hasClass("toggled") == false){
								$('.lisaa_arrow').attr("src", "images/arrow_up.png");
								$('.lisaa_arrow').addClass("toggled");
								$('.lisaacontent').show(200);
								$('.poista_arrow').attr("src", "images/arrow_down.png");
								$('.poista_arrow').removeClass("toggled");
								$('.poistacontent').hide(200);
						} else if ($('.lisaa_arrow').hasClass("toggled") == true) {
							$('.lisaa_arrow').attr("src", "images/arrow_down.png");
							$('.lisaa_arrow').removeClass("toggled");
							$('.lisaacontent').hide(200);
						}
					});
				});
				//Jos poista palvelu -nappia painetaan, piilotetaan palvelun lisäys ja avataan se.
				$(function() {
					$(".poista_openclose" ).click(function() {
						if ($('.poista_arrow').hasClass("toggled") == false){
							$('.poista_arrow').attr("src", "images/arrow_up.png");
							$('.poista_arrow').addClass("toggled");
							$('.poistacontent').show(200);
							$('.lisaa_arrow').attr("src", "images/arrow_down.png");
							$('.lisaa_arrow').removeClass("toggled");
							$('.lisaacontent').hide(200);
						} else if ($('.poista_arrow').hasClass("toggled") == true) {
							$('.poista_arrow').attr("src", "images/arrow_down.png");
							$('.poista_arrow').removeClass("toggled");
							$('.poistacontent').hide(200);
						}
					});
				});
				//Yritetään validoida palvelun lisäyslomake.
				$(function() {
					$('.lisaaform').validate({
						  rules: {
							  lisaapalvelu_kategoria: "required",
							  lisattavapalvelu: "required",
							  palvelunkesto: "required"
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "lisaapalvelu_kategoria") {
								error.appendTo(".add_errorcategory");
							  }	
							  else if (element.attr("name") == "lisattavapalvelu") {
								error.appendTo(".add_errorservice");
							  }
							  else if (element.attr("name") == "palvelunkesto") {
								error.appendTo(".add_errordiv");
							  } 
						  },
						  messages: {
							  lisattavapalvelu: "Tietoja puuttuu",
							  lisaapalvelu_kategoria: "Tietoja puuttuu",
							  palvelunkesto: "Tietoja puuttuu"
						  },
						  //Jos validointi menee läpi, viedään lisättävän palvelun tiedot erilliseen tiedostoon.
						  submitHandler: function(form) {
							  var palvelu = $('input.palvelunnimi').val();
							  var kategoria = $('.lisaaselect option:selected').val();
							  var palvelunkesto = $('.palvelunkesto option:selected').val();
							$.ajax({
								type: "POST",
								url: "lisaapalvelu.php",
								data: {palvelu: palvelu, kategoria: kategoria, palvelunkesto: palvelunkesto},
								success: function(result) 
								{
									//Jos tiedosto kertoo palvelun olevan jo, tiedotetaan siitä.
									if (result == "onjo_error") {
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Palvelu on jo olemassa</label>");
									}
									//Jos tiedosto käskee yrittämään uudestaan, tiedotetaan siitä.
									else if (result == "tryagain") {
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									}
									//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
									else if (result == "success") {
										$('.add_errordiv').replaceWith("<label id='success' for='success'>Palvelun lisääminen onnistui.</label>");
										setTimeout(function() {
											location.reload();
										}, 1000);
									}
									//Muussa tapauksessa pyydetään yrittämään uudestaan.
									else {
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log(result);
									}
								},
								//Jos ilmenee suoritusvirhe, pyydetään yrittämään uudestaan.
								error: function(result) {
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log(result);
								}
							});
						  }
						});
					});
					//Yritetään validoida palvelun poistamislomake.
					$(function() {
						$('.poistaform').validate({
						  rules: {
							  poistettavapalvelu: "required",
							  poistapalvelu_kategoria: "required",
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "poistapalvelu_kategoria") {
								error.appendTo(".remove_errorcategory");
							  }
							  else if (element.attr("name") == "poistettavapalvelu") {
								error.appendTo(".remove_errordiv");
							  }
						  },
						  messages: {
							  poistettavapalvelu: "Tietoja puuttuu",
							  poistapalvelu_kategoria: "Tietoja puuttuu",
						  },
						  //Jos validointi onnistuu, viedään tiedot erilliseen tiedostoon.
						  submitHandler: function(form) {
							$.ajax({
								type: "POST",
								url: "poistapalvelu.php",
								data: $('.poistaform').serialize(),
								success: function(result) 
								{
									//Jos tiedosto käskee yrittämään uudestaan, näytetään tieto siitä.
									if (result == "tryagain") {
										$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log(result);
									}
									//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
									else if (result == "success") {
										$('.remove_errordiv').replaceWith("<label id='success' for='success'>Palvelun poistaminen onnistui.</label>");
										setTimeout(function() {
											location.reload();
										}, 1000);
									}
									//Muussa tapauksessa kerrotaan epäonnistumisesta.
									else {
										$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Palvelun poistaminen epäonnistui. Tarkista, ettei se ole käytössä.</label>");
									}
								},
								//Jos ilmenee suoritusvirhe, pyydetään yrittämään uudestaan.
								error: function(result) {
									$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log(result);
								}
							});
						  }
						});
					});
					//Jos kategoriaa vaihdetaan kaikkien palveluiden näyttämisessä, viedään sen arvo erilliseen tiedostoon.
					$(function() {
						$('#palvelukategoria').change(function() {
							var palvelukategoria = $('#palvelukategoria').val();
							$.ajax({
								type: "POST",
								url: "haepalvelut.php",
								data: {palvelukategoria: palvelukategoria},
								success: function(result) 
								{
									//Jos tiedosto ei palauta palveluita, kerrotaan ettei kategoriasta löydy palveluita.
									if (result == "noservices") {
										$('.eipalveluita').show();
										$('.tableservices').html("");
									}
									//Jos tiedosto käskee yrittämään uudelleen, tiedotetaan siitä.
									else if (result == "tryagain") {
										$('.tryagain').show();
										$('.tableservices').html("");
									}
									//Muussa tapauksessa tehdään palveluista taulukko.
									else {
										$('.tableservices').html(result);
										$('.eipalveluita').hide();
									}
								},
								//Jos kyselyn suorittamisessa ilmenee virhe, pyydetään yrittämään uudestaan.
								error: function(result) {
									$('.tryagain').show();
									$('.tableservices').html("");
								}
							});
						});
					});
					//Jos kategoriaa vaihdetaan palvelun poistamisessa, viedään kategorian arvo erilliseen tiedostoon.
					$(function() {
						$('#poistapalvelu_kategoria').change(function() {
							var kategoria = $('#poistapalvelu_kategoria').val();
							$.ajax({
								type: "POST",
								url: "poista_haepalvelut.php",
								data: {kategoria: kategoria},
								success: function(result) 
								{
									//Jos tiedosto käskee yrittämään uudelleen, tiedotetaan siitä.
									if (result == "tryagain") {
										$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										$('.poistapalvelu_tulokset').html("");
										$('.poistapalvelu_tulokset').prop("disabled", true);
									}
									//Muussa tapauksessa pistetään tulos palveluselectiin.
									else {
										$('.poistapalvelu_tulokset').prop("disabled", false);
										$('.poistapalvelu_tulokset').html(result);
									}
								}
							});
						});
					});
					//Piilotetaan virheilmoitukset näppäinpainalluksella/klikkauksella.
					$(function() {
						$('*').click(function() {
							$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
							$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
						});
					});
					$(function() {
						$('*').keypress(function() {
							$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
							$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
						});
					});
				</script>
			</div>
		</div>
	</div>
	</div>
</div>
<br>
<div id="footer" class="text-center">
<p>&copy; Ajava 2018</p>
</div>