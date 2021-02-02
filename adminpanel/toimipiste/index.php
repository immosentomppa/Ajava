<?php
session_start();
/*Jos halutaan pysyä kirjautuneena, sessio on auki 10 vuotta ilman aktiivisuutta. Muussa tapauksessa 30 minuuttia. Kun 10 vuotta tai
30 sekuntia on kulunut, tuhotaan sessio.*/
if ($_SESSION['pysykirjautuneena'] == true) {
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 315619200)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
	} else {
		$_SESSION['LAST_ACTIVITY'] = time();
	}
}
else {
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
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
<script src="//cdn.jsdelivr.net/npm/jquery.scrollto@2.1.2/jquery.scrollTo.min.js"></script>
<?php
//Jos käyttäjä on admin, includataan admin css.
if ($_SESSION['kayttaja'] == "admin") {
	echo '<link rel="stylesheet" type="text/css" href="css/bootstrap_admin.css">';
}
//Muussa tapauksessa includataan normaali css.
else {
	echo '<link rel="stylesheet" type="text/css" href="css/bootstrap.css">';
}
?>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css">
<meta charset="utf-8">
</head>
<body>
<?php
//Jos käyttäjä on normaali, includataan normaalinavi, toimipistesivu ja laitetaan toimipistenappi aktiiviseksi.
date_default_timezone_set("Europe/Helsinki");
if (isset($_SESSION['kayttaja']) && !empty($_SESSION['kayttaja'])) {
	if ($_SESSION['kayttaja'] == "normaali") {
		include('navi.php');
		include('toimipiste.php');
		?>
		<script>$('li.toimipiste').addClass("active");</script>
		<?php
	}
	//Jos käyttäjä on admin, includataan adminin navi, toimipistesivu ja laitetaan toimipistenappi aktiiviseksi.
	else if ($_SESSION['kayttaja'] == "admin") {
		include('naviadmin.php');
		include('toimipiste.php');
		?>
		<script>$('li.toimipiste').addClass("active");</script>
		<?php
	}
}
//Muussa tapauksessa näytetään kirjautumissivulle ohjaava nappi.
else {
	echo '<div class="text-center"><h2>Et ole kirjautuneena.</h2><button class="btn btn-success" onclick="window.location.href=\'../../admin/\'">Tästä kirjautumissivulle.</button></div>';
}
?>
</body>
</html>