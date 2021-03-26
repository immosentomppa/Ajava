<?
//Jos toimipisteen arvo on asetettu ja se postataan -> asetetaan se muuttujaan.
if (isset($_POST['toimipiste']) && $_POST['toimipiste']) {
	$id = $_POST['toimipiste'];
	include('../../connect.php');
	try {
		//Poistetaan toimipiste annetun arvon perusteella.
		$query_tyontekija = $conn->prepare("DELETE FROM toimipiste WHERE id = :1");
		$query_tyontekija->bindValue(':1', $id);
		//Jos poistaminen onnistuu, palautetaan tieto siitä.
		if ($query_tyontekija->execute()) {
			echo "success";
		}
		//Jos ei onnistu, palautetaan uudelleenyrityspyyntö.
		else {
			echo "tryagain";
		}
		//Lopuksi tuhotaan yhteys.
		$conn = null;
	}
	//Jos jokin kohta kyselystä epäonnistuu, palautetaan uudelleenyrityspyyntö.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoa ei ole asetettu, palautetaan uudelleenyrityspyyntö
else {
	echo "tryagain";
}
?>