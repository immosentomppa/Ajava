<?php
session_start();
/*Jos halutaan pysyä kirjautuneena, sessio on auki 10 vuotta ilman aktiivisuutta. Muussa tapauksessa 30 minuuttia. Kun 10 vuotta tai
30 sekuntia on kulunut, tuhotaan sessio.*/
if ($_SESSION['pysykirjautuneena'] == true) {
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 315619200)) {
		session_unset();
		session_destroy();
	} else {
		$_SESSION['LAST_ACTIVITY'] = time();
	}
}
else {
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		session_unset();
		session_destroy();
	} else {
		$_SESSION['LAST_ACTIVITY'] = time();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Hallintapaneeli</title>
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
<?php
//Jos käyttäjä on normaali, includataan normaali css-tiedosto. Muussa tapauksessa adminin css-tiedosto.
if ($_SESSION['kayttaja'] == "admin") {
	echo '<link rel="stylesheet" type="text/css" href="css/bootstrap_admin.css">';
}
else {
	echo '<link rel="stylesheet" type="text/css" href="css/bootstrap.css">';
}

?>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css">
<meta charset="utf-8">
</head>
<body>
<?php
//Jos käyttäjä on normaali, includataan normaali navi ja etusivu sekä tehdään etusivunapista aktiivinen.
date_default_timezone_set("Europe/Helsinki");
if (isset($_SESSION['kayttaja']) && !empty($_SESSION['kayttaja'])) {
	if ($_SESSION['kayttaja'] == "normaali") {
		include('navi.php');
		include('etusivu.php');
		?>
		<script>$('li.etusivu').addClass("active");</script>
		<?php
	}
	//Jos käyttäjä on admin, includataan adminin navi ja etusivu sekä tehdään etusivunapista aktiivinen.
	else if ($_SESSION['kayttaja'] == "admin") {
		include('naviadmin.php');
		include('etusivu.php');
		?>
		<script>$('li.etusivu').addClass("active");</script>
		<?php
	}
}
//Muussa tapauksessa näytetään nappi, josta pääsee kirjautumissivulle.
else {
	echo '<div class="text-center"><h2>Et ole kirjautuneena.</h2><button class="btn btn-success" onclick="window.location.href=\'../admin\'">Tästä kirjautumissivulle.</button></div>';
}
?>
</body>
</html>