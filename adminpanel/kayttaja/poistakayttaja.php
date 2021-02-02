<?php
//Jos poistettavan käyttäjän id on asetettu ja se postataan.
if (isset($_POST['poistakayttaja']) && $_POST['poistakayttaja']) 
{
	$id = $_POST['poistakayttaja'];
	include('../../connect.php');
	try {
		//Yritetään poistaa käyttäjä.
		$query_del = $conn->prepare("DELETE FROM kayttajat WHERE id = :1");
		$query_del->bindValue(':1', $id);
		if ($query_del->execute()) {
			echo "success";
		}
		//Jos poistaminen ei onnistu, pyydetään yrittämään uudestaan.
		else {
			echo "tryagain";
		}
	}
	//Lopuksi tuhotaan tietokantayhteys.
	$conn = null;
	//Jos suorittamisessa ilmenee virhe, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoa ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>