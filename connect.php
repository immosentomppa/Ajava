<?php
//Tietokantayhteys
	$servername = "localhost";
	$username = <sensored>
	$password = <sensored>
	$dbname = <sensored>

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->exec("set names utf8");
	}
	// Check connection
	catch(PDOException $e) {
		echo "Virhe tietokantaan yhdistettäessä.";
	}
?>
