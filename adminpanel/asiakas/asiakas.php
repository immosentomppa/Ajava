			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Hae asiakasta</h2>
				<p>Euroopan uusien tietosuojalakien myötä asiakkaalla pitää olla oikeus nähdä, mitä tietoja hänestä säilytetään. Tästä voit hakea asiakkaan tietoja etunimen ja sukunimen perusteella.
				</p>
				<p>Asiakastiedot poistuvat automaattisesti joka päivä vanhojen varauksien poiston yhteydesssä.</p>
				<form class="haeform">
				<label for="etunimi">Haettavan asiakkaan etunimi</label>
				<input class="form-control" name="etunimi" id="etunimi" placeholder="Anna asiakkaan nimi"><div class="search_etunimi"></div>
				<label for="etunimi">Haettavan asiakkaan sukunimi</label>
				<input class="form-control" name="sukunimi" id="sukunimi" placeholder="Anna asiakkaan nimi"><div class="search_errordiv"></div>
				<input type="submit" class="btn btn-success" value="Hae">
				</form>
				<br>
				<div class="tableasiakas">
				</div>
				<script>
				$(function() {
					//Yritetään validoida asiakkaan hakulomake.
					$('.haeform').validate({
						  rules: {
							  etunimi: "required",
							  sukunimi: "required",
						  },
						  errorPlacement: function(error, element) {
							  if (element.attr("name") == "etunimi") {
								error.appendTo(".search_etunimi");
							}
							  else if (element.attr("name") == "sukunimi") {
								error.appendTo(".search_errordiv");
							}
						  },
						  messages: {
							  etunimi: "Tietoja puuttuu",
							  sukunimi: "Tietoja puuttuu",
						  },
						  //Jos validointi onnistuu, viedään lomakkeen tiedot erilliseen tiedostoon.
						  submitHandler: function(form) {
							$.ajax({
								type: "POST",
								url: "haeasiakas.php",
								data: $('.haeform').serialize(),
								success: function(result) 
								{
									//Jos tiedosto sanoo ettei asiakasta ole, tiedotetaan siitä.
									if (result == "eiole") {
										$('.search_errordiv').replaceWith("<label class='error search_errorlabel' for='error'>Haulla ei löytynyt asiakkaita.</label>");
										console.log("onjo");
										$('.tableasiakas').html("");
									}
									//Jos tiedosto käskee yrittämään uudestaan, tiedotetaan siitä.
									else if (result == "tryagain") {
										$('.search_errordiv').replaceWith("<label class='error search_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
										console.log("yritauudelleen");
										$('.tableasiakas').html("");
									}
									//Muussa tapauksessa pistetään asiakastaulukko näkyviin.
									else {
										$('.tableasiakas').html(result);
									}
								},
								//Jos ilmenee suoritusvirhe, käsketään yrittämään uudestaan.
								error: function(result) {
									$('.search_errordiv').replaceWith("<label class='error search_errorlabel' for='error'>Yritä myöhemmin uudelleen.</label>");
									console.log("suoritusvirhe");
								}
							});
						  }
						});
					});
					//Piilotetaan virheilmoitukset näppäinpainalluksella/kirjoituksella.
					$(function() {
						$('*').click(function() {
							$('.search_errorlabel').replaceWith("<div class='search_errordiv'></div>");
						});
					});
					$(function() {
						$('*').keypress(function() {
							$('.search_errorlabel').replaceWith("<div class='search_errordiv'></div>");
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