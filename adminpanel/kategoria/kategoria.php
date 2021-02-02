			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Kaikki kategoriat</h2><br>
				<?php
				//Tulostetaan kaikki kategoriat taulukkoon
				include('../../connect.php');
				$sql = $conn->prepare("SELECT id as kategoriaid, nimi as kategorianimi FROM kategoria");
				$sql->execute();
				if ($sql->rowCount() > 0) {
					echo '<div class="rullatable">';
					echo '<table class="table-bordered kategoriataulukko">
						<thead>
						<tr>
						<th>Nimi</th>
						<th>Palveluita kategoriassa</th>
						</tr>
						</thead>
						<tbody>';
					while ($rivikategoria = $sql->fetch()) {
						$sql_palveluita = $conn->prepare("SELECT COUNT(id) as palveluita FROM palvelu WHERE palvelu.kategoria = :1");
						$sql_palveluita->bindValue(':1', $rivikategoria['kategoriaid']);
						$sql_palveluita->execute();
						while ($rivipalveluita = $sql_palveluita->fetch()) {
							echo '<tr><td>' . $rivikategoria['kategorianimi'] . '</td><td>'. $rivipalveluita['palveluita'] . '</td></tr>';
						}
					}
					echo '</tbody>
						</table>
						</div>';
				}
				//Jos kategorioita ei ole, tiedotetaan siitä.
				else {
					"<p class='text-left'>Ei kategorioita</p>";
				}
				//Lopuksi tuhotaan tietokantayhteys.
				$conn = null;
				?>
				<br>
				<div class="text-left lisaa_openclose"><h2 id="lisaabtn" class="text-left">Lisää kategoria</h2><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label>Anna kategorian nimi</label>
				<input type="text" name="lisattavakategoria" class="form-control lisaainput" placeholder="Anna kategorian nimi"><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left poista_openclose"><h2 id="poistabtn" class="text-left">Poista kategoria</h2><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<p class="huomiopoista">Huom! Poistamalla kategorian poistat myös sen palvelut.</p>
				<form method="post" class="poistaform">
				<label>Valitse kategoria</label>
				<select name="poistettavakategoria" class="form-control poistaselect">
				<?php
					//Haetaan kaikki kategoriat kategorian poistoa varten.
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
						echo "<option value='noresults'>Ei kategorioita</option>";
					}
					//Lopuksi tuhotaan tietokantayhteys.
					$conn = null;
				?>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				<script>
				//Jos kategorian lisäämisnappia painetaan, piilotetaan kategorian poistaminen ja avataan se.
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
				//Jos kategorian poistamisnappia painetaan, piilotetaan kategorian lisääminen ja avataan se.
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
				//Yritetään validoida kategorian lisäämislomake.
				$('.lisaaform').validate({
				  rules: {
					  lisattavakategoria: "required",
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "lisattavakategoria") {
						error.appendTo(".add_errordiv");
					}
				  },
				  messages: {
					  lisattavakategoria: "Tietoja puuttuu",
				  },
				  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliseen tiedostoon.
				  submitHandler: function(form) {
					$.ajax({
						type: "POST",
						url: "lisaakategoria.php",
						data: $('.lisaaform').serialize(),
						success: function(result) 
						{
							//Jos tiedosto palauttaa kategorian olemassaolon, tiedotetaan siitä.
							if (result == "onjo_error") {
								$('.add_errordiv').replaceWith("<label id='onjo_error' class='add_errorlabel' for='error'>Kategoria on jo olemassa</label>");
							}
							//Jos tiedosto käskee yrittämään uudestaan, tiedotetaan siitä.
							else if (result == "tryagain") {
								$('.add_errordiv').replaceWith("<label id='onjo_error' class='add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							}
							//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
							else if (result == "success") {
								$('.add_errordiv').replaceWith("<label id='success' for='success'>Kategorian lisääminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muussa tapauksessa käsketään yrittämään uudestaan.
							else {
								console.log(result);
								$('.add_errordiv').replaceWith("<label id='onjo_error' class='add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							}
						},
						//Jos suoritus epäonnistuu, käsketään yrittämään uudestaan.
						error: function(result) {
							console.log(result);
							$('.add_errordiv').replaceWith("<label id='onjo_error' class='add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
						}
					});
				  }
				});
				//Yritetään validoida poistamislomake.
				$('.poistaform').validate({
				  rules: {
					  poistettavakategoria: "required",
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "poistettavakategoria") {
						error.appendTo(".remove_errordiv");
					}
				  },
				  messages: {
					  poistettavakategoria: "Tietoja puuttuu",
				  },
				  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliseen tiedostoon.
				  submitHandler: function(form) {
					$.ajax({
						type: "POST",
						url: "poistakategoria.php",
						data: $('.poistaform').serialize(),
						success: function(result) 
						{
							//Jos tiedosto käskee yrittämään uudelleen, tiedotetaan siitä.
							if (result == "tryagain") {
								$('.remove_errordiv').replaceWith("<label id='onjo_error' class='remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
							//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
							else if (result == "success") {
								$('.remove_errordiv').replaceWith("<label id='success' for='success'>Kategorian poistaminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muussa tapauksessa käsketään yrittämään uudestaan.
							else {
								console.log(result);
								$('.remove_errordiv').replaceWith("<label id='onjo_error' class='remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							}
						},
						//Jos ilmenee suoritusvirhe, käsketään yrittämään uudestaan.
						error: function(result) {
							console.log(result);
							$('.remove_errordiv').replaceWith("<label id='onjo_error' class='remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
						}
					});
				  }
				});
				//Piilotetaan virheilmoitukset näppäinpainalluksella/kirjoittamisella.
				$('*').click(function() {
					$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
					$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
				});
				$('*').keypress(function() {
					$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
					$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
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