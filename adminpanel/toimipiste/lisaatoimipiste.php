<?
//Jos lisättävän toimipisteen tiedot on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if (isset($_POST['toimipistenimi'], $_POST['toimipisteosoite'], $_POST['toimipistekaupunki'], 
$_POST['toimipistepuhnum'], $_POST['toimipistepostnum']) && $_POST['toimipistenimi'] && $_POST['toimipisteosoite'] && $_POST['toimipistekaupunki'] && $_POST['toimipistepuhnum'] && $_POST['toimipistepostnum']) {
	$nimi = $_POST['toimipistenimi'];
	$osoite = $_POST['toimipisteosoite'];
	$kaupunki = $_POST['toimipistekaupunki'];
	$puhnum = $_POST['toimipistepuhnum'];
	$postnum = $_POST['toimipistepostnum'];
	include('../../connect.php');
	try {
		//Katsotaan, onko kyseinen toimipiste jo olemassa.
		$query_find = $conn->prepare("SELECT * FROM toimipiste WHERE nimi = :1 AND osoite = :2 AND postitoimipaikka = :3 AND puhnum = :4 AND postinumero = :5");
		$query_find->bindValue(':1', $nimi);
		$query_find->bindValue(':2', $osoite);
		$query_find->bindValue(':3', $kaupunki);
		$query_find->bindValue(':4', $puhnum);
		$query_find->bindValue(':5', $postnum);
		$query_find->execute();
		//Jos on, palautetaan tieto siitä.
		if ($query_find->rowCount() > 0) {
			echo "onjo_error";
		}
		//Jos ei ole, lisätään toimipiste tietokantaan.
		else if ($query_find->rowCount() == 0) {
			$query_add = $conn->prepare("INSERT INTO toimipiste (nimi, osoite, puhnum, postinumero, postitoimipaikka) VALUES (:1, :2, :3, :4, :5)");
			$query_add->bindValue(':1', $nimi);
			$query_add->bindValue(':2', $osoite);
			$query_add->bindValue(':3', $puhnum);
			$query_add->bindValue(':4', $postnum);
			$query_add->bindValue(':5', $kaupunki);
			//Jos tietokantaan lisäys onnistuu, palautetaan onnistuminen.
			if ($query_add->execute()) {
				echo "success";
			}
			//Jos ei onnistu, pyydetään yrittämään uudelleen.
			else {
				echo "tryagain";
			}
		}
		//Lopuksi tuhotaan yhteys.
		$conn = null;
	}
	//Jos jokin kohta kyselyssä epäonnistuu, pyydetään yrittämään uudelleen.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudelleen.
else {
	echo "tryagain";
}
?>