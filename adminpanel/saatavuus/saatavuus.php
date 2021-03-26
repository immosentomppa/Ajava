			<div class="kategoriadiv text-left">
				
				<h2 class="text-left kategoriaotsikko">Saatavilla olevat ajat</h2><br>
				<script>
				</script>
				<?php
				//Haetaan saatavilla olevat ajat taulukkoon.
				include('../../connect.php');
				$sql = $conn->prepare("SELECT saatavilla.id as id, saatavilla.paivamaara as paiva, saatavilla.aika, saatavilla.loppuu as loppuu, saatavilla.varattu as varaukset, palvelu.nimi as palvelunimi, tyontekija.nimi as tyontekijanimi, toimipiste.nimi as toimipistenimi FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN tyontekija ON saatavilla.tyontekija = tyontekija.id INNER JOIN toimipiste ON saatavilla.toimipiste = toimipiste.id WHERE saatavilla.varattu = :1");
				$sql->bindValue(':1', "Ei");
				$sql->execute();
				if ($sql->rowCount() > 0) {
					echo '<div class="rullatable">';
					echo '<table class="table-bordered kategoriataulukko">';
					echo "<thead>
							<tr>
							<th>Palvelu</th>
							<th>Toimipiste</th>
							<th>Työntekijä</th>
							<th>Päivämäärä</th>
							<th>Alkaa</th>
							<th>Loppuu</th>
							<th>Varattu</th>
							</tr>
							</thead>
							<tbody>";
					while ($row = $sql->fetch()) {
						$date = new DateTime($row['paiva']);
						$oikeepaivamaara = $date->format("d.m.Y");
						$aika = date("H:i", strtotime($row['aika']));
						$loppuu = date("H:i", strtotime($row['loppuu']));
						echo '<td>'.$row['palvelunimi'].'</td><td>'.$row['toimipistenimi'].'</td><td>'.$row['tyontekijanimi'].'</td><td>'.$oikeepaivamaara.'</td><td>'.$aika.'</td><td>'. $loppuu . '</td><td>'.$row['varaukset'].'</td></tr>';
					}
					echo '</tbody>
					</table>
					</div>';
				//Jos aikoja ei ole, tiedotetaan siitä.
				} else {
					echo "<p class='text-left'>Ei saatavilla olevia aikoja.</p>";
				}
				?>
				<br>
				<div class="text-left lisaa_openclose"><h3 id="lisaabtn" class="text-left">Lisää varattava aika</h3><img class="lisaa_arrow" src="images/arrow_down.png"></div>
				<div class="lisaacontent text-left">
				<form method="post" class="lisaaform">
				<label for="palvelu">Valitse palvelu</label>
				<select name="saatavuuspalvelu" class="form-control lisaaselect">
				<?php
					//Haetaan kaikki palvelut ajan lisäämistä varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM palvelu ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse palvelu</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos palveluita ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei palveluita</option>";
					}

				?>
				</select><div class="add_palveludiv"></div>
				<label for="toimipiste">Valitse toimipiste</label>
				<select name="saatavuustoimipiste" class="form-control lisaaselect">
				<?php
					//Haetaan toimipisteet ajan lisäämistä varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM toimipiste ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse toimipiste</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos aikoja ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei toimipisteitä</option>";
					}
				?>
				</select><div class="add_toimipistediv"></div>
				<label for="tyontekija">Valitse työntekijä</label>
				<select name="saatavuustyontekija" class="form-control lisaaselect">
				<?php
					//Haetaan työntekijät ajan lisäämistä varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT nimi, id FROM tyontekija ORDER BY id");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse työntekijä</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
						}
					}
					//Jos työntekijöitä ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei työntekijöitä</option>";
					}
				?>
				</select><div class="add_tyontekijadiv"></div>
				<label for="aika">Valitse päivämäärä</label>
				<input type="text" name="saatavuuspaivamaara" id="datepicker" class="form-control lisaainput" placeholder="Valitse päivämäärä">
				<script type="text/javascript">
				//Luodaan uusi datepicker päivämäärän valitsemista varten ja tehdään siihen tarvittavat asetukset.
				$('#datepicker').datepicker({
					format: "dd.mm.yyyy",
					weekStart: 1,
					language: 'fi',
					startDate: new Date(),
					todayHighlight: true
				});
				</script><div class="add_paivamaaradiv"></div>
				<label for="aika">Valitse aika</label>
				<input type="time" name="saatavuusaika" id="timepicker" class="form-control"><div class="add_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä">
				</form>
				</div>
				<div class="text-left poista_openclose"><h3 id="poistabtn" class="text-left">Poista varattava aika</h3><img class="poista_arrow" src="images/arrow_down.png"></div>
				<div class="poistacontent text-left">
				<form method="post" class="poistaform">
				<label for="aikaid">Valitse ajan ID</label>
				<select name="poistettavaid" class="form-control poistaselect">
				<?php
					//Haetaan aikojen id:t ajan poistoa varten.
					include("../../connect.php");
					$sql = $conn->prepare("SELECT id FROM saatavilla WHERE saatavilla.varattu = :1 ORDER BY id");
					$sql->bindValue(':1', "Ei");
					$sql->execute();
					if ($sql->rowCount() > 0) {
						echo '<option value="poistettu" disabled selected>Valitse ajan ID</option>';
						while ($row = $sql->fetch()) {
							echo "<option value='".$row["id"]."'>ID: ".$row['id']."</option>";
						}
					}
					//Jos aikoja ei ole, tiedotetaan siitä.
					else {
						echo "<option value='noresults' disabled selected>Ei aikoja</option>";
					}
					//Lopuksi tuhotaan yhteys.
					$conn = null;
				?>
				</select><div class="remove_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Lähetä"><br>
				</form>
				</div>
				<script>
				$(function() {
					//Jos lisää aika -nappia painetaan, piilotetaan poisto ja avataan se.
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
				//Jos poista aika -nappia painetaan, piilotetaan avaus ja avataan se.
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
				//Yritetään validoida ajan lisäyslomake.
				$(function() {
					$('.lisaaform').validate({
					  rules: {
						  saatavuuspalvelu: "required",
						  saatavuustoimipiste: "required",
						  saatavuustyontekija: "required",
						  saatavuuspaivamaara: "required",
						  saatavuusaika: "required"
					  },
					  errorPlacement: function(error, element) {
						  if (element.attr("name") == "saatavuuspalvelu") {
							error.appendTo(".add_palveludiv");
						  }
						  else if (element.attr("name") == "saatavuustoimipiste") {
							error.appendTo(".add_toimipistediv");
						  }
						  else if (element.attr("name") == "saatavuustyontekija") {
							error.appendTo(".add_tyontekijadiv");
						  }
						  else if (element.attr("name") == "saatavuuspaivamaara") {
							error.appendTo(".add_paivamaaradiv");
						  }
						  else if (element.attr("name") == "saatavuusaika") {
							error.appendTo(".add_errordiv");
						  }
					  },
					  messages: {
						  saatavuuspalvelu: "Tietoja puuttuu",
						  saatavuustoimipiste: "Tietoja puuttuu",
						  saatavuustyontekija: "Tietoja puuttuu",
						  saatavuuspaivamaara: "Tietoja puuttuu",
						  saatavuusaika: "Tietoja puuttuu"
					  },
					  //Jos validointi onnistuu, viedään ajan tiedot erilliseen tiedostoon.
					  submitHandler: function(form) {
						$.ajax({
							type: "POST",
							url: "lisaavarattava.php",
							data: $('.lisaaform').serialize(),
							success: function(result) 
							{
								//Jos tiedosto kertoo ettei työntekijällä ole vapaata kyseiseen aikaan, tiedotetaan siitä.
								if (result == "onjo_error") {
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Työntekijällä ei ole vapaana kyseiseen aikaan.</label>");
									console.log("onjo");
								}
								//Jos tiedosto käskee yrittämään uudelleen, tiedotetaan siitä.
								else if (result == "tryagain") {
									console.log("yritauudestaan");
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								}
								//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä ja päivitetään sivu.
								else if (result == "onnistui" ||result == "onnistui2") {
									$('.add_errordiv').replaceWith("<label id='success' for='success'>Varattavan ajan lisääminen onnistui.</label>");
									console.log("onnistui");
									setTimeout(function() {
										location.reload();
									}, 1000);
								}
								//Jos tiedosto sanoo ajan olevan liian aikaisin, tiedotetaan siitä.
								else if (result.includes("liianaikaisin")) {
									console.log("liianaikaisin");
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Ajan pitää olla vähintään 2 tunnin päässä.</label>");
								}
								//Muutoin pyydetään yrittämään uudestaan.
								else {
									$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log(result);
								}
							},
							//Jos kyselyn suoritus epäonnistuu, pyydetään yrittämään uudestaan.
							error: function(result) {
								$('.add_errordiv').replaceWith("<label class='error add_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log("suoritusvirhe");
							}
						});
					  }
					});
				});
				//Yritetään validoida poistettavan ajan lomake
				$(function() {
					$('.poistaform').validate({
					  rules: {
						  poistettavaid: "required",
					  },
					  errorPlacement: function(error, element) {
						  if (element.attr("name") == "poistettavaid") {
						  error.appendTo(".remove_errordiv");
						}
					  },
					  messages: {
						  poistettavaid: "Tietoja puuttuu",
					  },
					  //Jos validointi onnistuu, viedään poistettavan ajan arvo erilliselle tiedostolle.
					  submitHandler: function(form) {
						  var poistettavaid = $('.poistaselect').val();
						$.ajax({
							type: "POST",
							url: "poistavarattava.php",
							data: {poistettavaid: poistettavaid},
							success: function(result) 
							{
								//Jos tiedosto pyytää yrittämään uudelleen, tiedotetaan siitä.
								if (result == "tryagain") {
									$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log("onjo");
								}
								//Jos tiedosto palauttaa onnistumisen, tiedotetaan siitä.
								else if (result == "onnistui") {
									$('.remove_errordiv').replaceWith("<label id='success' for='success'>Varattavan ajan poistaminen onnistui.</label>");
									setTimeout(function() {
										location.reload();
									}, 1000);
									console.log("onnistui");
								}
								//Muussa tapauksesssa pyydetään yrittämään uudestaan.
								else {
									$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log("muuvirhe");
								}
							},
							//Jos kyselyn suoritus epäonnistui, pyydetään yrittämään uudestaan.
							error: function(result) {
								$('.remove_errordiv').replaceWith("<label class='error remove_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
								console.log("suoritusvirhe");
							}
						});
					  }
					});
				});
				//Piilotetaan virheviestit klikkauksen/näppäinpainalluksen jälkeen.
				$(function() {
					$('*').click(function() {
						$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
						$('.remove_errorlabel').replaceWith("<div class='remove_errordiv'></div>");
					});
				});
				$(function() {
					$('*').keypress(function() {
						$('.add_errorlabel').replaceWith("<div class='add_errordiv'></div>");
						$('.remove_errordiv').replaceWith("<div class='remove_errordiv'></div>");
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