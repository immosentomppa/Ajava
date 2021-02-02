<?
//Jos palvelun tiedot on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if (isset($_POST['palvelu'], $_POST['kategoria'], $_POST['palvelunkesto']) && $_POST['palvelu'] && $_POST['kategoria'] && $_POST['palvelunkesto']) {
	$kesto = $_POST['palvelunkesto'];
	$kategoria = $_POST['kategoria'];
	$palvelu = $_POST['palvelu'];
	include('../../connect.php');
	try {
		//Tarkistetaan, onko palvelu jo olemassa.
		$query_find = $conn->prepare("SELECT * FROM palvelu WHERE nimi = :1");
		$query_find->bindValue(':1', $palvelu);
		$query_find->execute();
		//Jos on, palautetaan tieto siitä.
		if ($query_find->rowCount() > 0) {
			echo "onjo_error";
		}
		//Jos ei ole, lisätään se tietokantaan.
		else if ($query_find->rowCount() == 0){
			$query_add = $conn->prepare("INSERT INTO palvelu (nimi, kategoria, kesto) VALUES (:1, :2, :3)");
			$query_add->bindValue(':1', $palvelu);
			$query_add->bindValue(':2', $kategoria);
			$query_add->bindValue(':3', $kesto);
			//Jos lisäys onnistuu, palautetaan tieto onnistumisesta.
			if ($query_add->execute()) {
				echo "success";
			}
			//Muutoin pyydetään yrittämään uudestaan.
			else {
				echo "tryagain";
			}
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos jokin kohta epäonnistuu, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>