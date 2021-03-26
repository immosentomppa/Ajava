 <?php
include('connect.php');
//Jos kategoria ja palvelu on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if (isset($_POST['kategoria'], $_POST['palvelu']) && $_POST['kategoria'] && $_POST['palvelu']) {
	$kategoria = $_POST['kategoria'];
	$palvelu = $_POST['palvelu'];
	//Haetaan työntekijät halutuilla ehdoilla, joilla on varaamaton aika.
	$sql = $conn->prepare("SELECT DISTINCT saatavilla.tyontekija, tyontekija.nimi FROM saatavilla INNER JOIN tyontekija on saatavilla.tyontekija = tyontekija.id INNER JOIN palvelu on saatavilla.palvelu = palvelu.id INNER JOIN kategoria on palvelu.kategoria = kategoria.id WHERE saatavilla.palvelu = :1 AND palvelu.kategoria = :2 AND saatavilla.varattu = :3" );
	$sql->bindValue(':1', $palvelu);
	$sql->bindValue(':2', $kategoria);
	$sql->bindValue(':3', "Ei");
	$sql->execute();
	//Jos tuloksia löytyi, viedään ne työntekijäselectille.
	if ($sql->rowCount() > 0) {
		echo "<option selected='selected' value='poistettu' disabled >Valitse työntekijä</option>";
		echo "<option value='kukatahansa'>Kuka tahansa</option>";
		while ($row = $sql->fetch()) {
			echo "<option value='".$row["tyontekija"]."'>".$row["nimi"]."</option>";
		}
	}
	//Jos tuloksia ei löytynyt, tiedotetaan siitä.
	else {
		echo "<option value='noresults'>Ei tuloksia</option>";
	}
//Lopuksi katkaistaan tietokantayhteys.
$conn = null;
}
//Jos arvoja ei ole, näytetään virheilmoitus.
else {
	echo "errorfile";
}
?>