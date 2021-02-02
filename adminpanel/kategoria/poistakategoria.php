<?
//Jos poistettavan kategorian arvo on asetettu ja se postataan -> asetetaan se muuttujaan.
if (isset($_POST['poistettavakategoria']) && $_POST['poistettavakategoria']) {
	$kategoria = $_POST['poistettavakategoria'];
	include('../../connect.php');
	try {
		//Poistetaan kaikki kyseisen kategorian palvelut.
		$query_palvelu = $conn->prepare("DELETE FROM palvelu WHERE kategoria = :1");
		$query_palvelu->bindValue(':1', $kategoria);
		if ($query_palvelu->execute()) {
			//Jos kategorian palveluiden poistaminen onnistui, poistetaan itse kategoria.
			$query_kategoria = $conn->prepare("DELETE FROM kategoria WHERE id = :1");
			$query_kategoria->bindValue(':1', $kategoria);
			//Jos kategorian poistaminen onnistui, palautetaan onnistuminen.
			if ($query_kategoria->execute()) {
				echo "success";
			}
			//Muussa tapauksessa käsketään yrittämään uudelleen.
			else {
				echo "tryagain";
			}
		}
		//Jos kategorian palveluiden poistaminen ei onnistunut, käsketään yrittämään uudestaan.
		else {
			echo "tryagain";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos suorittamisessa ilmenee virhe, käsketään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, käsketään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>