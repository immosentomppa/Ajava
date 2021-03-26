<?
//Jos työntekijä on asetettu ja sen arvo postataan -> asetetaan se muuttujaan.
if (isset($_POST['tyontekija']) && $_POST['tyontekija']) {
	$tyontekija = $_POST['tyontekija'];
	include('../../connect.php');
	//Poistetaan työntekijä annetun arvon perusteella.
	$query_tyontekija = $conn->prepare("DELETE FROM tyontekija WHERE id = :1");
	$query_tyontekija->bindValue(':1', $tyontekija);
	//Jos poisto onnistuu, palautetaan onnistuminen.
	if ($query_tyontekija->execute()) {
		echo "success";
	}
	//Muussa tapauksessa käsketään yrittämään uudelleen.
	else {
		echo "tryagain";
	}
	//Lopuksi tuhotaan yhteys.
	$conn = null;
}
//Jos arvoja ei ole asetettu, käsketään yrittämään uudelleen.
else {
	echo "tryagain";
}
?>