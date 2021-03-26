<div class="container">
<div class="backgroundarea">
	<div class="row">
		<div class="col-lg-12 col-md-10 col sm-6">
		<br>
		<img class="logo" src="images/ajava_logo.png">
		<br>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
			  <li class="etusivu"><a href="../">Aloitus</a></li>
				<li class="kategoria"><a href="../kategoria">Kategoria</a></li>
				<li class="palvelu"><a href="../palvelu">Palvelu</a></li>
				<li class="tyontekija"><a href="../tyontekija">Työntekijä</a></li>
				<li class="toimipiste"><a href="../toimipiste">Toimipiste</a></li>
				<li class="saatavuus"><a href="../saatavuus">Saatavuus</a></li>
				<li class="varaukset"><a href="../varaukset">Varaukset</a></li>
				<li class="asiakas"><a href="../asiakas">Asiakas</a></li>
				<li class="kayttaja"><a href="../kayttaja">Käyttäjä</a></li>
				<li><a class="logout">Kirjaudu ulos</a></li>
			  </ul>
			</div>
		  </div>
		</nav>
		<script>
		//Yritetään uloskirjautumista
		$('.logout').click(function() {
			$.ajax({
				type: "POST",
				url: "logout.php",
				success: function(result) 
				{
					//Jos onnistuu, viedään käyttäjä takaisin kirjautumissivulle.
					if (result == "onnistui") {
						window.location.href = '../../admin/#logoutsuccess';	
					}
					//Jos ei onnistu, pistetään alerttia.
					else {
						alert("Uloskirjautuminen ei onnistunut");
					}
				}
			});
		});
		</script>