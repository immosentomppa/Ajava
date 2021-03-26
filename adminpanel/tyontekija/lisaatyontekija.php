<?
//Jos lisättävän työntekijän nimi on asetettu ja se postataan -> asetetaan se muuttujaan.
if (isset($_POST['lisattavatyontekija']) && $_POST['lisattavatyontekija']) {
	$tyontekija = $_POST['lisattavatyontekija'];
	include('../../connect.php');
	try {
	//Katsotaan, onko samanniminen työntekijä jo olemassa.
		$query_find = $conn->prepare("SELECT * FROM tyontekija WHERE nimi = :1");
		$query_find->bindValue(':1', $tyontekija);
		$query_find->execute();
		//Jos on, tiedotetaan siitä.
		if ($query_find->rowCount() > 0) {
			echo "onjo_error";
		}
		//Muutoin lisätään kyseinen työntekijä tietokantaan.
		else if ($query_find->rowCount() == 0) {
			$query_add = $conn->prepare("INSERT INTO tyontekija (nimi) VALUES (:1)");
			$query_add->bindValue(':1', $tyontekija);
			//Jos lisäys onnistuu, palautetaan onnistuminen.
			if ($query_add->execute()) {
				echo "success";
			}
			//Muussa tapauksessa käsketään yrittämään uudelleen.
			else {
				echo "tryagain";
			}
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;	
	}
	//Jos virheitä ilmenee, käsketään yrittämään uudelleen.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, käsketään yrittämään uudelleen.
else {
	echo "tryagain";
}
?>