<?
//Jos poistettavan palvelun tiedot on asetettu ja ne postataan -> tallennetaan ne muuttujiin.
if (isset($_POST['poistapalvelu_kategoria'], $_POST['poistettavapalvelu']) && $_POST['poistapalvelu_kategoria'] && $_POST['poistettavapalvelu']) {
	$kategoria = $_POST['poistapalvelu_kategoria'];
	$palvelu = $_POST['poistettavapalvelu'];
	include('../../connect.php');
	try {
		//Yritetään poistaa palvelu annettujen tietojen perusteella.
		$query_del = $conn->prepare("DELETE FROM palvelu WHERE kategoria = :1 AND id = :2");
		$query_del->bindValue(':1', $kategoria);
		$query_del->bindValue(':2', $palvelu);
		//Jos poisto onnistuu, palautetaan tieto onnistumisesta.
		if ($query_del->execute()) {
			echo "success";
		}
		//Muussa tapauksessa pyydetään yrittämään uudestaan.
		else {
			echo "tryagain";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos jokin kohta epäonnistuu, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole asetettu, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>