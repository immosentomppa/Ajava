<?
//Jos käyttäjän tiedot on asetettu ja ne postataan -> tallennetaan ne muuttujiin.
if (isset($_POST['lisattavarooli'], $_POST['lisattavasalasana'], $_POST['lisattavakayttajatunnus']) && $_POST['lisattavarooli'] && $_POST['lisattavasalasana'] && $_POST['lisattavakayttajatunnus']) {
	$kayttajatunnus = $_POST['lisattavakayttajatunnus'];
	$salasana_raaka = $_POST['lisattavasalasana'];
	$rooli = $_POST['lisattavarooli'];
	include('../../connect.php');
	try {
		//Tarkistetaan, onko käyttäjä jo olemassa.
		$query_find = $conn->prepare("SELECT * FROM kayttajat WHERE kayttajatunnus = :1 AND role = :2");
		$query_find->bindValue(':1', $kayttajatunnus);
		$query_find->bindValue(':2', $salasana);
		$query_find->execute();
		//Jos on, tiedotetaan siitä.
		if ($query_find->rowCount() > 0) {
			echo "onjo_error";
		//Jos ei ole, hashataan salasana ja lisätään käyttäjä tietokantaan.
		} else {
			$query_add = $conn->prepare("INSERT INTO kayttajat (kayttajatunnus, salasana, role) VALUES (:1, :2, :3)");
			$query_add->bindValue(':1', $kayttajatunnus);
			$query_add->bindValue(':2', password_hash($salasana_raaka, PASSWORD_DEFAULT));
			$query_add->bindValue(':3', $rooli);
			//Jos tietokantaan lisäys onnistui, palautetaan onnistuminen.
			if ($query_add->execute()) {
				echo "success";
			}
			//Jos ei onnistunut, pyydetään yrittämään uudelleen.
			else {
				echo "tryagain";
			}
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos suorittamisessa ilmenee virhe, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>