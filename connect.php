<?php
//Tietokantayhteys
	$servername = "localhost";
	$username = "ajava";
	$password = "8Ukiu@BcfyEZUYDqaGnem_j!Jr";
	$dbname = "ajava";

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