<!DOCTYPE html>
<html>
<head>
<title>Hallintapaneelin kirjautuminen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.fi.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.css">
<script src="//cdn.jsdelivr.net/npm/jquery.scrollto@2.1.2/jquery.scrollTo.min.js"></script>
<meta charset="utf-8">
</head>
<body>
<div class="container">
<div class="backgroundarea">
	<div class="row">
		<div class="col-lg-12 form-group"><br>
			<img class="logo" src="images/ajava_logo.png"><br><br>
			<div id="lomakkeet">
			<nav class="navbar navbar-default">
				<ul class="nav navbar-nav">
				  <li class="active kirjautuminen"><a href="#tologin" class="kirjaudubtn">Kirjaudu</a></li>
				  <li class="tunnushukassa"><a href="#totunnushukassa" class="tunnushukassabtn">Tunnus hukassa</a></li>
				</ul>
			</nav>
			<a class="hiddenanchor" id="totunnushukassa"></a>
			<a class="hiddenanchor" id="tologin"></a>
			<div id="loginerror" hidden>Tarkista käyttäjätunnus ja salasana.</div>
			<div id="tryagain" hidden>Yritä myöhemmin uudelleen.</div>
			<script>
			//Jos urlin perässä on uloskirjautumisessa tuleva pääte, näytetään viesti uloskirjautumisesta.
			$(function(){
				var activediv = $(location).attr('hash');
				if (activediv === "#logoutsuccess") {
					$('#logoutsuccess').prop('hidden', false);
				}
			})
			//Jos kirjautumista painetaan, scrollataan sen kohdalle ja asetetaan se aktiiviseksi.
			$(function() {
				$('.kirjautuminen').click(function() {
					$('body').scrollTo('#tologin', 500);
					$('.tunnushukassa').removeClass("active");
					$('.kirjautuminen').addClass("active");
				});
			});
			//Jos tunnus hukassa -kohtaa painetaan, scrollataan tekstin kohdalle ja asetetaan se aktiiviseksi.
			$(function() {
				$('.tunnushukassa').click(function() {
					$('body').scrollTo('#totunnushukassa', 500);
					$('.tunnushukassa').addClass("active");
					$('.kirjautuminen').removeClass("active");
				});
			});
			</script>
			<div id="logoutsuccess" hidden>Uloskirjautuminen onnistui.</div>
			<div id="kirjaudu" class="animate">
			<h2>Kirjaudu</h2>
			<form action="" method="post" id="kirjautumislomake"><br>
			<label for="kayttajatunnus" class="kayttislabel">Käyttäjätunnus</label><br>
			<input name="kayttajatunnus" autocomplete="username" placeholder="Käyttäjätunnus" type="text" class="modallogin"><br>
			<label for="salasana" class="salislabel">Salasana</label><br>
			<input name="salasana" type="password" placeholder="Salasana" autocomplete="current-password" class="modallogin">
			<label class="staylogged" for="pysykirjautuneena" ><input type="checkbox" id="pysykirjautuneena" name="pysykirjautuneena"> Pysy kirjautuneena</label>
			<br><input type="submit" value="Kirjaudu" class="btn btn-success kirjaudubtn">
			</form>
			</div>
			<div id="tunnushukassa" class="animate">
			<h2>Tunnus hukassa</h2>
			<p>Jos olet hukannut tunnuksesi, ota yhteyttä järjestelmänvalvojaan.</p>
			</div>
			</div>
			<script>
			//Validoidaan kirjautumislomake.
			$(function() {
			  $('#kirjautumislomake').validate({
				  rules: {
					  kayttajatunnus: "required",
					  salasana: "required",
				  },
				  messages: {
					  kayttajatunnus: "Tietoja puuttuu",
					  salasana: "Tietoja puuttuu"
				  },
			  //Jos validointi menee läpi, viedään lomakkeen tiedot login.php-tiedostolle.
			  submitHandler: function(form) {
				$.ajax({
					type: "POST",
					url: "login.php",
					data: $('#kirjautumislomake').serialize(),
					success: function(result) 
					{
						//Jos tiedot ovat väärät, näytetään virheilmoitus.
						if (result == "wrongdetails") {
							$('#loginerror').prop('hidden', false);
						}
						//Jos tiedot ovat oikein, ohjataan käyttäjä hallintapaneeliin.
						else if (result == "success") {
							window.location.href = '../adminpanel/index.php';
						}
						//Muutoin käsketään yrittämään uudelleen.
						else {
							$('#tryagain').prop('hidden', false);
							console.log(result);
						}
					},
					//Jos kyselyn suorittamisessa tapahtuu virhe, näytetään virheilmoitus.
					error: function(result) {
						$('#tryagain').prop('hidden', false);
						console.log(result);
					}
				});
			  }
			 });
			});
			//Kun klikataan mitä tahansa, piilotetaan virheilmoitukset.
			$(function() {
				$('*').click(function() {
					$('#logoutsuccess').prop('hidden', true);
					$('#tryagain').prop('hidden', true);
					$('#loginerror').prop('hidden', true);
				});
			});
		  </script>
		</div>
	</div>
</div>
</div>
</body>
</html>