			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Kaikki toimipisteet</h2><br>
				<?php
				//Haetaan kaikki toimipisteet taulukkoon.
				include('../../connect.php');
				$sql = $conn->prepare("SELECT nimi, osoite, puhnum, postitoimipaikka, postinumero FROM toimipiste");
				$sql->execute();
				echo '<div class="rullatable">';
				echo '<table class="table-bordered kategoriataulukko">
				<thead>
				<tr>
				<th>Nimi</th>
				<th>Osoite</th>
				<th>Puhelinnumero</th>
				<th>Postinumero</th>
				<th>Postitoimipaikka</th>
				</tr>
				</thead>
				<tbody>';
				if ($sql->rowCount() > 0) {			
					while ($row = $sql->fetch()) {
						echo '<tr><td>'.$row['nimi'].'</td><td>'.$row['osoite'].'</td><td>'.$row['puhnum'].'</td><td>'.$row['postinumero'].'</td><td>'.$row['postitoimipaikka'].'</td></tr>';
					}
					echo '</tbody>
							</table>
							</div>';
				}
				//Jos toimipisteitä ei ole, tiedotetaan siitä.
				else {
					echo "<p class='text-left'>Ei toimipisteitä.</p>";
				}
				//Lopuksi tuhotaan tietokantayhteys.
				$conn = null;
				?>
				<br>
				<div class="text-left lisaa_openclose"><h2 id="lisaabtn" class="text-left">Lisää toimipiste</h2><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label>Anna toimipisteen nimi</label>
				<input type="text" name="toimipistenimi" class="form-control toimipistenimi" placeholder="Anna toimipisteen nimi"><div class="add_name"></div>
				<label>Anna toimipisteen osoite</label>
				<input type="text" name="toimipisteosoite" class="form-control toimipisteosoite" placeholder="Anna toimipisteen osoite"><div class="add_address"></div>
				<label>Anna toimipisteen puhelinnumero</label>
				<input type="tel" name="toimipistepuhnum" class="form-control toimipistepuhnum" placeholder="Anna toimipisteen puhelinnumero" pattern="[\d+()\- ]{7,25}"><div class="add_tel"></div>
				<label>Anna toimipisteen postitoimipaikka</label>
				<input type="text" name="toimipistekaupunki" class="form-control toimipistekaupunki" placeholder="Anna toimipisteen postitoimipaikka"><div class="add_city"></div>
				<label>Anna toimipisteen postinumero</label>
				<input type="text" pattern="\d{5}" name="toimipistepostnum" class="form-control toimipistepostnum" placeholder="Anna toimipisteen nimi"><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left poista_openclose"><h2 id="poistabtn" class="text-left">Poista toimipiste</h2><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<form method="post" class="poistaform">
				<select name="poistettavatoimipiste" class="form-control poistaselect">
				<?php
					//Haetaan kaikki toimipisteet poistettavan toimipisteen valintaan.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM toimipiste ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse toimipiste</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos toimipisteitä ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei toimipisteitä</option>";
					}
					//Lopuksi tuhotaan yhteys.
					$conn = null;
				?>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				<script>
				//Jos lisää toimipiste -nappulaa painetaan, piilotetaan muut ja avataan sen sisältö.
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
				//Jos poista toimipiste -nappulaa painetaan, piilotetaan muut ja avataan sen sisältö.
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
				//Validoidaan toimipisteen lisäyslomake.
				$('.lisaaform').validate({
				  rules: {
					  toimipistenimi: "required",
					  toimipisteosoite: "required",
					  toimipistepuhnum: "required",
					  toimipistekaupunki: "required",
					  toimipistepostnum: "required"
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "toimipistenimi") {
						error.appendTo(".add_name");
					  }
					  else if (element.attr("name") == "toimipisteosoite") {
						error.appendTo(".add_address");
					  }
					  else if (element.attr("name") == "toimipistepuhnum") {
						error.appendTo(".add_tel");
					  }
					  else if (element.attr("name") == "toimipistekaupunki") {
						error.appendTo(".add_city");
					  }
					  else if (element.attr("name") == "toimipistepostnum") {
						error.appendTo(".add_errordiv");
					  }
				  },
				  messages: {
					  toimipistenimi: "Tietoja puuttuu",
					  toimipisteosoite: "Tietoja puuttuu",
					  toimipistepuhnum: "Tietoja puuttuu",
					  toimipistekaupunki: "Tietoja puuttuu",
					  toimipistepostnum: "Tietoja puuttuu"
				  },
				  //Jos validointi onnistuu, viedään lisättävän toimipisteen tiedot erilliseen tiedostoon.
				  submitHandler: function(form) {
					$.ajax({
						type: "POST",
						url: "lisaatoimipiste.php",
						data: $('.lisaaform').serialize(),
						success: function(result) 
						{
							//Jos vastauksena on toimipisteen jo olemassaolo, näytetään viesti siitä.
							if (result == "onjo_error") {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Toimipiste on jo olemassa</label>");
								console.log(result);
							}
							//Jos vastauksena on uudelleenyrittäminen, näytetään viesti siitä.
							else if (result == "tryagain") {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
							//Jos vastauksena on onnistuminen, näytetään viesti siitä ja päivitetään sivu.
							else if (result == "success") {
								$('.add_errordiv').replaceWith("<label id='success' for='success'>Toimipisteen lisääminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muussa tapauksessa pyydetään yrittämään uudelleen.
							else {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
						},
						//Jos kyselyn suorittaminen ei onnistu, pyydetään yrittämään uudelleen.
						error: function(result) {
							$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							console.log(result);
						}
					});
				  }
				});
				//Yritetään validoida toimipisteen poistamislomake.
				$('.poistaform').validate({
				  rules: {
					  poistettavatoimipiste: "required",
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "poistettavatoimipiste") {
					  error.appendTo(".remove_errordiv");
					}
				  },
				  messages: {
					  poistettavatoimipiste: "Tietoja puuttuu",
				  },
				  //Jos validointi onnistuu, viedään poistettavan toimipisteen arvo erilliseen tiedostoon.
				  submitHandler: function(form) {
					  var toimipiste = $('.poistaselect').val();
					$.ajax({
						type: "POST",
						url: "poistatoimipiste.php",
						data: {toimipiste: toimipiste},
						success: function(result) 
						{
							//Jos tiedosto palauttaa käskyn yrittää uudelleen, näytetään viesti siitä.
							if (result == "tryagain") {
							$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							console.log(result);
							}
							//Jos tiedosto palauttaa onnistumisen, näytetään viesti siitä.
							else if (result == "success") {
								$('.remove_errordiv').replaceWith("<label id='success' for='success'>Toimipisteen poistaminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muussa tapauksessa käsketään yrittämään uudelleen
							else {
								$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
						},
						//Jos kyselyn suorittaminen ei onnistu, käsketään yrittämään uudelleen.
						error: function(result) {
							$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							console.log(result);
						}
					});
				  }
				});
				//Piilotetaan mahdolliset virheviestit klikkauksella/näppäinpainalluksella.
				$('*').click(function() {
					$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
					$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
				});
				$('*').keypress(function() {
					$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
					$('.remove_errordiv').replaceWith("<div class='remove_errordiv'></div>");
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