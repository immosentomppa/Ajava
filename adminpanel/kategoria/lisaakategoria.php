<?
//Jos lisättävän kategorian nimi on asetettu ja se postataan -> asetetaan se muuttujaan.
if (isset($_POST['lisattavakategoria']) && $_POST['lisattavakategoria']) {
	$kategoria = $_POST['lisattavakategoria'];
	include('../../connect.php');
	try {
		//Tarkistetaan, onko kategoria jo olemassa.
		$query_find = $conn->prepare("SELECT * FROM kategoria WHERE nimi = :1");
		$query_find->bindValue(':1', $kategoria);
		$query_find->execute();
		$rows = $query_find->fetchAll();
		//Jos on, tiedotetaan siitä.
		if ($query_find->rowCount() > 0) {
			echo "onjo_error";
		}
		//Jos ei ole, lisätään kategoria tietokantaan.
		else if ($query_find->rowCount() == 0) {
			$query_add = $conn->prepare("INSERT INTO kategoria (nimi) VALUES (:1)");
			$query_add->bindValue(':1', $kategoria);
			//Jos kategorian lisääminen onnistui, tiedotetaan siitä.
			if ($query_add->execute()) {
				echo "success";
			}
			//Muussa tapauksessa käsketään yrittämään uudestaan.
			else {
				echo "tryagain";
			}
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos suorittamisessa ilmenee virhe, käsketään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoa ei ole, käsketään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>