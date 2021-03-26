			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Kaikki käyttäjät</h2>
				<?php
				//Haetaan kaikki käyttäjät ja heidän tietonsa taulukkoon.
				include('../../connect.php');
				$sql = $conn->prepare("SELECT id as kayttajaid, kayttajatunnus, role FROM kayttajat");
				$sql->execute();
				echo '<div class="rullatable">';
				echo '<table class="table-bordered kategoriataulukko">';
				echo "<thead>
						<tr>
						<th>Käyttäjätunnus</th>
						<th>Rooli</th>
						</tr>
						</thead>
						<tbody>";
				if ($sql->rowCount() > 0) {
					while ($rows = $sql->fetch()) {
						if ($rows['role'] == "normal") {
							$rooli = "Normaali käyttäjä";
						}
						else if ($rows['role'] == "admin") {
							$rooli = "Järjestelmänvalvoja";
						}
						echo '<tr><td>'.$rows['kayttajatunnus'].'</td><td>' . $rooli . '</td></tr>';
					}
					echo '</tbody></table></div>';
				}
				//Jos käyttäjiä ei ole, tiedotetaan siitä.
				else {
					echo "<p class='text-left'>Ei käyttäjiä.</p>";
				}
				//Lopuksi tuhotaan tietokantayhteys.
				$conn = null;
				?>
				<br>
				<div class="eikayttajia text-left" hidden>
				<label id="noservices" for="error">Ei palveluita kategoriassa.</label>
				</div>
				<div class="tableservices">
				</div>
				<div class="text-left lisaa_openclose"><h2 id="lisaabtn" class="text-left">Lisää käyttäjä</h2><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label for="rooli">Valitse rooli</label>
				<select class="form-control lisaaselect" id="rooli" name="lisattavarooli">
				<option value="poistettu" disabled selected>Valitse rooli</option>
				<option value="admin">Järjestelmänvalvoja</option>
				<option value="normal">Normaali käyttäjä</option>
				</select><div class="add_role"></div>
				<label for="kayttajatunnus">Anna käyttäjätunnus</label>
				<input type="text" name="lisattavakayttajatunnus" class="form-control lisaainput" placeholder="Anna käyttäjätunnus"><div class="add_kayttajatunnus"></div>
				<label for="salasana">Anna käyttäjän salasana</label>
				<input type="text" name="lisattavasalasana" class="form-control lisaainput" placeholder="Anna käyttäjän nimi"><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left palauta_openclose"><h2 id="palautabtn" class="text-left">Palauta salasana</h2><img class="palauta_arrow" src="images/arrow_down.png"></div>
				<div class="palautacontent text-left">
				<form method="post" class="palautaform">
				<label for="kayttaja">Valitse käyttäjä</label>
				<select class="form-control palautaselect" name="palautasalasana">
				<?php
					//Haetaan kaikki käyttäjät salasanan palauttamista varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT kayttajatunnus, id FROM kayttajat ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse käyttäjä</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["kayttajatunnus"]."</option>";
						}
					}
					//Jos käyttäjiä ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults'>Ei käyttäjiä</option>";
					}
					//Lopuksi tuhotaan tietokantayhteys.
					$conn = null;
				?>
				</select>
				<div class="restore_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Palauta">
				</form>
				</div>
				<div class="text-left poista_openclose"><h2 id="poistabtn" class="text-left">Poista käyttäjä</h2><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<form method="post" class="poistaform">
				<label for="poistakayttaja">Valitse käyttäjä</label>
				<select class="form-control poistaselect" name="poistakayttaja">
				<?php
					//Haetaan käyttäjät käyttäjän poistamista varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT kayttajatunnus, id FROM kayttajat ORDER BY id");
					$sql->execute();
					if ($sql->rowCount()> 0) {
						echo '<option value="poistettu" disabled selected>Valitse käyttäjä</option>';
						while($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["kayttajatunnus"]."</option>";
						}
					}
					//Jos käyttäjiä ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults'>Ei käyttäjiä</option>";
					}
					//Lopuksi tuhotaan tietokantayhteys.
					$conn = null;
				?>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				<script>
				$(function() {
					//Jos käyttäjän lisäämisnappia painetaan, piilotetaan salasanan palauttaminen sekä käyttäjän poisto ja avataan lisääminen.
					$(".lisaa_openclose" ).click(function() {
						if ($('.lisaa_arrow').hasClass("toggled") == false){
								$('.lisaa_arrow').attr("src", "images/arrow_up.png");
								$('.lisaa_arrow').addClass("toggled");
								$('.lisaacontent').show(200);
								$('.poista_arrow').attr("src", "images/arrow_down.png");
								$('.poista_arrow').removeClass("toggled");
								$('.poistacontent').hide(200);
								$('.palauta_arrow').attr("src", "images/arrow_down.png");
								$('.palauta_arrow').removeClass("toggled");
								$('.palautacontent').hide(200);
						} else if ($('.lisaa_arrow').hasClass("toggled") == true) {
							$('.lisaa_arrow').attr("src", "images/arrow_down.png");
							$('.lisaa_arrow').removeClass("toggled");
							$('.lisaacontent').hide(200);
						}
					});
				});
				//Jos käyttäjän salasanan palautusnappia painetaan, piilotetaan käyttäjän lisääminen sekä poistaminen ja avataan salasanan palautus.
				$(function() {
					$(".palauta_openclose" ).click(function() {
						if ($('.palauta_arrow').hasClass("toggled") == false){
								$('.palauta_arrow').attr("src", "images/arrow_up.png");
								$('.palauta_arrow').addClass("toggled");
								$('.palautacontent').show(200);
								$('.poista_arrow').attr("src", "images/arrow_down.png");
								$('.poista_arrow').removeClass("toggled");
								$('.poistacontent').hide(200);
								$('.lisaa_arrow').attr("src", "images/arrow_down.png");
								$('.lisaa_arrow').removeClass("toggled");
								$('.lisaacontent').hide(200);
						} else if ($('.palauta_arrow').hasClass("toggled") == true) {
							$('.palauta_arrow').attr("src", "images/arrow_down.png");
							$('.palauta_arrow').removeClass("toggled");
							$('.palautacontent').hide(200);
						}
					});
				});
				//Jos käyttäjän poistonappia painetaan, piilotetaan käyttäjän lisääminen sekä salasanan palauttaminen ja avataan käyttäjän poistaminen.
				$(function() {
					$(".poista_openclose" ).click(function() {
						if ($('.poista_arrow').hasClass("toggled") == false){
							$('.poista_arrow').attr("src", "images/arrow_up.png");
							$('.poista_arrow').addClass("toggled");
							$('.poistacontent').show(200);
							$('.lisaa_arrow').attr("src", "images/arrow_down.png");
							$('.lisaa_arrow').removeClass("toggled");
							$('.lisaacontent').hide(200);
							$('.palauta_arrow').attr("src", "images/arrow_down.png");
							$('.palauta_arrow').removeClass("toggled");
							$('.palautacontent').hide(200);
						} else if ($('.poista_arrow').hasClass("toggled") == true) {
							$('.poista_arrow').attr("src", "images/arrow_down.png");
							$('.poista_arrow').removeClass("toggled");
							$('.poistacontent').hide(200);
						}
					});
				});
				//Yritetään validoida käyttäjän lisäämislomake.
				$(function() {
					$('.lisaaform').validate({
						  rules: {
							  lisattavarooli: "required",
							  lisattavakayttajatunnus: "required",
							  lisattavasalasana: "required"
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "lisattavakayttajatunnus") {
								error.appendTo(".add_kayttajatunnus");
							  }	
							  else if (element.attr("name") == "lisattavasalasana") {
								error.appendTo(".add_errordiv");
							  }
							  else if (element.attr("name") == "lisattavarooli") {
								error.appendTo(".add_role");
							  }
						  },
						  messages: {
							  lisattavarooli: "Tietoja puuttuu",
							  lisattavakayttajatunnus: "Tietoja puuttuu",
							  lisattavasalasana: "Tietoja puuttuu"
						  },
						  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliseen tiedostoon.
						  submitHandler: function(form) {									
							$.ajax({
								type: "POST",
								url: "lisaakayttaja.php",
								data: $('.lisaaform').serialize(),
								success: function(result) 
								{
									//Jos tiedosto palauttaa käyttäjän olemassaolon, tiedostetaan siitä.
									if (result == "onjo_error") {
										console.log(result);
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Käyttäjä on jo olemassa</label>");
									}
									//Jos tiedosto käskee yrittämään uudestaan, tiedotetaan siitä.
									else if (result == "tryagain") {
										console.log(result);
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									}
									//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
									else if (result == "success") {
										$('.add_errordiv').replaceWith("<label id='success' for='success'>Käyttäjän lisääminen onnistui.</label>");
										setTimeout(function() {
											location.reload();
										}, 1000);
									}
									//Muussa tapauksessa käsketään yrittämään uudestaan.
									else {
										console.log(result);
										$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									}
								},
								//Jos suorituksessa ilmenee virhe, käsketään yrittämään uudestaan.
								error: function(result) {
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log(result);
								}
							});
						  }
						});
					});
					//Yritetään validoida salasanan palauttamislomake.
				$(function() {
					$('.palautaform').validate({
						  rules: {
							  palautasalasana: "required"
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "palautasalasana") {
								error.appendTo(".restore_errordiv");
							  }
						  },
						  messages: {
							  palautasalasana: "Tietoja puuttuu"
						  },
						  //Jos validointi onnistuu, viedään käyttäjän id erilliseen tiedostoon.
						  submitHandler: function(form) {
							$.ajax({
								type: "POST",
								url: "palautakayttaja.php",
								data: $('.palautaform').serialize(),
								success: function(result) 
								{
									//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
									if (result == "success") {
										$('.restore_errordiv').replaceWith("<label id='success' for='success'>Tunnuksen lähetys onnistui.<br>Tsekkaa varalta myös roskaposti.</label>");
									}
									//Jos tiedosto käskee yrittämään uudelleen, tiedotetaan siitä.
									else if (result == "tryagain") {
										$('.restore_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log(result);
									}
									//Muussa tapauksessa käsketään yrittämään uudelleen.
									else {
										$('.restore_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log(result);
									}
								},
								//Jos suorituksessa ilmenee virhe, käsketään yrittämään uudestaan.
								error: function(result) {
									$('.restore_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log(result);
								}
							});
						  }
						});
					});
					//Yritetään validoida käyttäjän poistamislomake.
					$(function() {
						$('.poistaform').validate({
						  rules: {
							  poistakayttaja: "required",
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "poistakayttaja") {
								error.appendTo(".remove_errordiv");
							  }
						  },
						  messages: {
							  poistakayttaja: "Tietoja puuttuu"
						  },
						  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliseen tiedostoon.
						  submitHandler: function(form) {
							$.ajax({
								type: "POST",
								url: "poistakayttaja.php",
								data: $('.poistaform').serialize(),
								success: function(result) 
								{
									//Jos tiedosto käskee yrittämään uudestaan, tiedotetaan siitä.
									if (result == "tryagain") {
										$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log(result);
									}
									//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
									else if (result == "success") {
										$('.remove_errordiv').replaceWith("<label id='success' for='success'>Käyttäjän poistaminen onnistui.</label>");
										setTimeout(function() {
											location.reload();
										}, 1000);
									}
									//Muussa tapauksessa käsketään yrittämään uudestaan.
									else {
										console.log(result);
										$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									}
								},
								//Jos suorittamisessa ilmenee virhe, käsketään yrittämään uudestaan.
								error: function(result) {
									console.log(result);
									$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								}
							});
						  }
						});
					});
					//Piilotetaan virheilmoitukset klikkauksella/näppäinpainalluksella.
					$(function() {
						$('*').click(function() {
							$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
							$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
							$('#success').replaceWith("<div class='restore_errordiv'></div>");
						});
					});
					$(function() {
						$('*').keypress(function() {
							$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
							$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
							$('#success').replaceWith("<div class='restore_errordiv'></div>");
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