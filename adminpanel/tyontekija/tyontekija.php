			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Kaikki työntekijät</h2><br>
				<?php
				//Haetaan kaikki työntekijät taulukkoon.
				include('../../connect.php');
				$sql = $conn->prepare("SELECT nimi FROM tyontekija");
				$sql->execute();
				if ($sql->rowCount() > 0) {
					echo '<div class="rullatable">';
					echo '<table class="table-bordered kategoriataulukko">
						<thead>
						<tr>
						<th>Nimi</th>
						</tr>
						</thead>
						<tbody>';
					while ($row = $sql->fetch()) {
						echo '<tr><td>'.$row['nimi'].'</td></tr>';
					}
					echo '</tbody>
						</table>
						</div>';
				}
				//Jos työntekijöitä ei ole, kerrotaan se.
				else {
					echo '<p class="text-left">Ei työntekijöitä.</p>';
				}
				//Lopuksi tuhotaan yhteys.
				$conn = null;
				?>

				<br>
				<div class="text-left lisaa_openclose"><h2 id="lisaabtn" class="text-left">Lisää työntekijä</h2><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label>Anna työntekijän nimi</label>
				<input type="text" name="lisattavatyontekija" class="form-control lisaainput" placeholder="Anna työntekijän nimi"><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left poista_openclose"><h2 id="poistabtn" class="text-left">Poista työntekijä</h2><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<form method="post" class="poistaform">
				<label>Valitse työntekijä</label>
				<select name="poistettavatyontekija" class="form-control poistaselect">
				<?php 
					include("../../connect.php");
					//Haetaan kaikki työntekijät työntekijän poistoa varten.
					$sql = $conn->prepare("SELECT nimi, id FROM tyontekija ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse työntekijä</option>';
						while($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos tuloksia ei ole, ilmoitetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei työntekijöitä</option>";
					}
					//Lopuksi tuhotaan yhteys.
					$conn = null;
				?>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				
				<script>
				//Jos lisää työntekijä -painiketta painetaan, piilotetaan muut ja avataan sen sisältö.
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
				//Jos poista työntekijä -painiketta painetaan, piilotetaan muut ja avataan sen sisältö.
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
				//Funktio lisää työntekijä -lomakkeen validointiin.
				$('.lisaaform').validate({
				  rules: {
					  lisattavatyontekija: "required",
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "lisattavatyontekija") {
					  error.appendTo(".add_errordiv");
					  }
				  },
				  messages: {
					  lisattavatyontekija: "Tietoja puuttuu",
				  },
				  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliselle tiedostolle.
				  submitHandler: function(form) {
					$.ajax({
						type: "POST",
						url: "lisaatyontekija.php",
						data: $('.lisaaform').serialize(),
						success: function(result) 
						{
							//Jos palautusviesti kertoo työntekijän olevan jo listassa, näytetään virhe.
							if (result == "onjo_error") {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Työntekijä on jo olemassa</label>");
							}
							//Jos palautusviesti käskee yrittämään uudelleen, näytetään virhe.
							else if (result == "tryagain") {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							}
							//Jos palautusviesti antaa onnistumisen, näytetään viesti siitä ja päivitetään sivu.
							else if (result == "success") {
								$('.add_errordiv').replaceWith("<label id='success' for='success'>Työntekijän lisääminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muutoin näytetään virheilmoitus.
							else {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
						},
						//Jos kysely ei onnistu, näytetään virheilmoitus.
						error: function(result) {
							$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							console.log(result);
						}
					});
				  }
				});
				//Yritetään validoida työntekijän poistamislomake.
				$('.poistaform').validate({
				  rules: {
					  poistettavatyontekija: "required",
				  },
				  errorPlacement: function(error, element) {
					  if (element.attr("name") == "poistettavatyontekija") {
					  error.appendTo(".remove_errordiv");
					}
				  },
				  messages: {
					  poistettavatyontekija: "Tietoja puuttuu",
				  },
				  //Jos validointi onnistuu, viedään poistettava työntekijä erilliseen tiedostoon.
				  submitHandler: function(form) {
					  var tyontekija = $('.poistaselect').val();
					$.ajax({
						type: "POST",
						url: "poistatyontekija.php",
						data: {tyontekija: tyontekija},
						success: function(result) 
						{
							//Jos tulos käskee yrittämään uudelleen, näytetään viesti siitä.
							if (result.includes("tryagain")) {
								$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
							//Jos tulos on onnistuminen, näytetään viesti siitä.
							else if (result.includes("success")) {
								$('.remove_errordiv').replaceWith("<label id='success' class='remove_errorlabel' for='success'>Työntekijän poistaminen onnistui.</label>");
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
							//Muussa tapauksessa käsketään yrittämään uudelleen.
							else {
								$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log(result);
							}
						},
						//Jos kysely epäonnistuu, käsketään yrittämään uudelleen.
						error: function(result) {
							$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
							console.log(result);
						}
					});
				  }
				});
				//Piilotetaan kyselyn virheilmoitukset klikkauksen/näppäinpainalluksen jälkeen.
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