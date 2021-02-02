 <?php
include('connect.php');
//Jos kategoria, palvelu, työntekijä, toimipiste ja päivämäärä on valittu ja ne postataan -> asetetaan ne muuttujiin.
if(isset($_POST['kategoria'], $_POST['palvelu'], $_POST['tyontekija'], $_POST['toimipiste'], $_POST['valittuPaiva']) && $_POST['kategoria'] && $_POST['palvelu'] && $_POST['tyontekija'] && $_POST['toimipiste'] && $_POST['valittuPaiva']){
	$kategoria=$_POST['kategoria'];
	$palvelu=$_POST['palvelu'];
	$tyontekija=$_POST['tyontekija'];
	$toimipiste=$_POST['toimipiste'];
	$valittuPaiva=$_POST['valittuPaiva'];
	try {
		//Jos työntekijävalinta on kuka tahansa, haetaan saatavilla olevat ajat ilman työntekijäehtoa.
		if ($tyontekija == "kukatahansa") {
			$sql = $conn->prepare("SELECT aika, saatavilla.id as saatavillaid, tyontekija.nimi as tyontekijanimi, tyontekija AS tyontekijanumero FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN tyontekija ON saatavilla.tyontekija = tyontekija.id INNER JOIN kategoria ON palvelu.kategoria = kategoria.id WHERE palvelu.kategoria = :1 AND saatavilla.palvelu = :2 AND saatavilla.toimipiste = :3 AND saatavilla.paivamaara = :4 AND saatavilla.varattu = :5 ORDER BY saatavilla.aika ASC");
			$sql->bindValue(':1', $kategoria);
			$sql->bindValue(':2', $palvelu);
			$sql->bindValue(':3', $toimipiste);
			$sql->bindValue(':4', $valittuPaiva);
			$sql->bindValue(':5', "Ei");
			$sql->execute();
			//Jos tuloksia on, tehdään jokaisesta tuloksesta nappi jolle annetaan oleelliset tiedot aikaan liitten.
			if ($sql->rowCount() > 0) {
				$tietoArray = array();
				echo "<hr>";
				echo "<script>$('#valittavatAjat').prop('hidden', false);</script>";
				while ($row = $sql->fetch())
				{
					$saatavillaid = $row['saatavillaid'];
					$tyontekijanumero = $row['tyontekijanumero'];
					$tyontekijanimi = $row['tyontekijanimi'];
					$aikajono = strtotime($row['aika']);
					$aika = date("H:i", $aikajono);
					array_push($tietoArray, $tyontekijanumero);
					array_push($tietoArray, $tyontekijanimi);
					array_push($tietoArray, $aika);
					array_push($tietoArray, $saatavillaid);
					echo "<button type='button' data-toggle='modal' data-target='#myModal' onclick='lataaModal(".json_encode($tietoArray).")' value='".$aika."'>" .$aika. "</button>";
				}
				//Tuloksien palauttamisen jälkeen tuhotaan lista.
				unset ($tietoArray);
			}
			//Jos tuloksia ei ole, tiedotetaan siitä.
			else {
				echo "noresults";
			}
			//Lopuksi katkaistaan tietokantayhteys.
			$conn = null;
		}
		//Jos työntekijävalinta ei ole kuka tahansa, haetaan aluksi työntekijän nimi ja id ja lisätään ne listaan.
		else if ($tyontekija != "kukatahansa"){
			$query_prepare = $conn->prepare("SELECT tyontekija.id, nimi FROM tyontekija WHERE tyontekija.id = :1");
			$query_prepare->bindValue(':1', $tyontekija);
			$query_prepare->execute();
			$tietoArray = array();
			if ($query_prepare->rowCount() > 0) {
				while ($row = $query_prepare->fetch()) {
					$tyontekijanumero = $row['id'];
					$tyontekijanimi = $row['nimi'];
				}
				array_push($tietoArray, $tyontekijanumero);
				array_push($tietoArray, $tyontekijanimi);
				//Haetaan ajat halutuilla ehdoilla ja työntekijällä.
				$sql = $conn->prepare("SELECT aika, saatavilla.id as saatavillaid FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN kategoria ON palvelu.kategoria = kategoria.id WHERE palvelu.kategoria = :1 AND saatavilla.palvelu = :2 AND saatavilla.tyontekija = :3 AND saatavilla.toimipiste = :4 AND saatavilla.paivamaara = :5 AND saatavilla.varattu = :6 ORDER BY saatavilla.aika ASC");
				$sql->bindValue(':1', $kategoria);
				$sql->bindValue(':2', $palvelu);
				$sql->bindValue(':3', $tyontekija);
				$sql->bindValue(':4', $toimipiste);
				$sql->bindValue(':5', $valittuPaiva);
				$sql->bindValue(':6', "Ei");
				$sql->execute();
				//Jos tuloksia on, tehdään jokaisesta tuloksesta nappi jolle annetaan oleelliset tiedot aikaan liittyen.
				if ($sql->rowCount() > 0) {
					echo "<hr>";
					echo "<script>$('#valittavatAjat').prop('hidden', false);</script>";
					while ($row = $sql->fetch())
					{
						$aikajono = strtotime($row['aika']);
						$aika = date("H:i", $aikajono);
						$saatavillaid = $row['saatavillaid'];
						array_push($tietoArray, $aika);
						array_push($tietoArray, $saatavillaid);
						echo "<button type='button' data-toggle='modal' data-target='#myModal' onclick='lataaModal(".json_encode($tietoArray).")' value='".$aika."'>" .$aika. "</button>";
					}
					//Tuloksien palauttamisen jälkeen tuhotaan lista.
					unset ($tietoArray);
				//Jos tuloksia ei ole, tiedotetaan siitä.	
				}
				else {
					echo "noresults";
				}
			}
			//Jos työntekijää ei löydy, näytetään virheilmoitus.
			else {
				echo "errorfile";
			}
			//Lopuksi katkaistaan tietokantayhteys.
			$conn = null;
		}
		//Muutoin näytetään virheilmoitus.
		else {
			echo "errorfile";
		}
	}
	//Jos jokin kohta epäonnistuu, näytetään virheilmoitus.
	catch (PDOException $e) {
		echo "errorfile";
	}
}
//Jos arvoja ei ole, näytetään virheilmoitus.
else {
	echo "errorfile";
}
?>