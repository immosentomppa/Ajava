<?php
include('connect.php');
//Jos kategoria, palvelu, työntekijä sekä toimipiste on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if(isset($_POST['kategoria'],  $_POST['palvelu'], $_POST['tyontekija'], $_POST['toimipiste']) && $_POST['kategoria'] && $_POST['palvelu'] && $_POST['tyontekija'] && $_POST['toimipiste']){
    $kategoria=$_POST['kategoria'];
    $palvelu=$_POST['palvelu'];
    $tyontekija=$_POST['tyontekija'];
    $toimipiste=$_POST['toimipiste'];
	//Jos työntekijän arvo on kukatahansa
	if ($tyontekija == "kukatahansa") {
		//Etsitään päivämäärät valituilla arvoilla, jotka ovat vähintään 2 tunnin päässä ja saatavilla.
		$sql = $conn->prepare("SELECT paivamaara FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN kategoria on palvelu.kategoria = kategoria.id WHERE palvelu.kategoria = :1 AND palvelu.id = :2 AND saatavilla.toimipiste = :3 AND saatavilla.varattu = :4 AND saatavilla.aikalukujono >= :5");
		$sql->bindValue(':1', $kategoria);
		$sql->bindValue(':2', $palvelu);
		$sql->bindValue(':3', $toimipiste);
		$sql->bindValue(':4', "Ei");
		$sql->bindValue(':5', strtotime("+2 hours"));
		$sql->execute();
		$paivamaarat = array();
		//Jos päivämääriä löytyi, tehdään niistä lista ja palautetaan se, jonka jälkeen tuhotaan se.
		if ($sql->rowCount() > 0) {
			while($row = $sql->fetch())
			{
				array_push($paivamaarat, $row['paivamaara']); 
			}
			echo json_encode($paivamaarat);
			unset($paivamaarat);
		}
		//Jos päivämääriä ei löytynyt, tiedotetaan siitä.
		else {
			echo "nodates";
		}
		//Lopuksi katkaistaan tietokantayhteys.
		$conn = null;
	}
	//Jos työntekijävalinta ei ole kuka tahansa, haetaan päivämäärät ja lisätään ehtoihin työntekijä.
    else if ($tyontekija != "kukatahansa"){
		$sql = $conn->prepare("SELECT paivamaara FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN kategoria on palvelu.kategoria = kategoria.id WHERE palvelu.kategoria = :1 AND palvelu.id = :2 AND saatavilla.tyontekija = :3 AND saatavilla.toimipiste = :4 AND saatavilla.varattu = :5 AND saatavilla.aikalukujono >= :6");
		$sql->bindValue(':1', $kategoria);
		$sql->bindValue(':2', $palvelu);
		$sql->bindValue(':3', $tyontekija);
		$sql->bindValue(':4', $toimipiste);
		$sql->bindValue(':5', "Ei");
		$sql->bindValue(':6', strtotime("tomorrow"));
		$sql->execute();
		$paivamaarat = array();
		//Jos tuloksia löytyy, lisätään ne päivämäärälistaan ja palautetaan se, jonka jälkeen tuhotaan kyseinen lista.
		if ($sql->rowCount() > 0) {
			while($row = $sql->fetch())
			{
				array_push($paivamaarat, $row['paivamaara']); 
			}
			echo json_encode($paivamaarat);
			unset($paivamaarat);
		}
		//Jos päivämääriä ei löytynyt, tiedotetaan siitä.
		else {
			echo "nodates";
		}
		//Lopuksi katkaistaan tietokantayhteys.
		$conn = null;
	}
	//Jos työntekijän arvo on jokin muu, näytetään virheilmoitus.
	else {
		echo "errorfile";	
	}
}
//Jos arvoja ei ole, näytetään virheilmoitus.
else {
	echo "errorfile";
}
?>