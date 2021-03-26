<?
//Jos poistettavan ajan id on asetettu ja se postataan -> tallennetaan se muuttujaan.
if (isset($_POST['poistettavaid']) && $_POST['poistettavaid']) {
	$id = $_POST['poistettavaid'];
	include('../../connect.php');
	try {	
		//Yritetään poistaa aika id:n perusteella.
		$query_tyontekija = $conn->prepare("DELETE FROM saatavilla WHERE id = :1");
		$query_tyontekija->bindValue(':1', $id);
		//Jos poisto onnistuu, palautetaan onnistuminen.
		if ($query_tyontekija->execute()) {
			echo "onnistui";
		}
		//Muussa tapauksessa pyydetään yrittämään uudestaan.
		else {
			echo "tryagain";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos jossain ilmenee virhe, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos id:tä ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>