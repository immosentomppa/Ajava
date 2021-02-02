<?php
//Kirjaudutaan ulos
	session_start();
	$onnistuiko = session_destroy();
	if ($onnistuiko) {
		session_unset();
		echo "onnistui";
		exit();
	}
	else {
		echo "error";
		exit();
	}
?>