 <?php
include('connect.php');
//Jos kategoria, palvelu ja työntekijä on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if (isset($_POST['kategoria'], $_POST['palvelu'], $_POST['tyontekija']) && $_POST['kategoria'] && $_POST['palvelu'] && $_POST['tyontekija']) {
	$kategoria = $_POST['kategoria'];
	$palvelu = $_POST['palvelu'];
	$tyontekija = $_POST['tyontekija'];
	//Jos työntekijävalinta on kuka tahansa, haetaan toimipisteet ilman sitä ehtona.
	if ($tyontekija == "kukatahansa") {
		$sql = $conn->prepare("SELECT DISTINCT saatavilla.toimipiste, toimipiste.nimi FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN kategoria ON palvelu.kategoria = kategoria.id INNER JOIN tyontekija ON saatavilla.tyontekija = tyontekija.id INNER JOIN toimipiste ON saatavilla.toimipiste = toimipiste.id WHERE kategoria = :1 AND palvelu = :2 AND saatavilla.varattu = :3");
		$sql->bindValue(':1', $kategoria);
		$sql->bindValue(':2', $palvelu);
		$sql->bindValue(':3', "Ei");
		$sql->execute();
		//Jos tuloksia löytyy, viedään ne toimipisteselectille.
		if ($sql->rowCount() > 0) {
			echo "<option selected='selected' value='poistettu' disabled >Valitse toimipiste</option>";
			while ($row = $sql->fetch()) {
				
				echo "<option value='".$row["toimipiste"]."'>".$row["nimi"]."</option>";

			}
		}
		//Jos tuloksia ei löydy, tiedotetaan siitä.
		else {
			echo "<option value='noresults'>Ei tuloksia</option>";
		}
		//Lopuksi katkaistaan tietokantayhteys.
		$conn = null;
	}
	//Jos työntekijä ei ole kuka tahansa, lisätään se hakuehtoihin ja haetaan toimipisteet.
	else if ($tyontekija != "kukatahansa") {
		$sql = $conn->prepare("SELECT DISTINCT saatavilla.toimipiste, toimipiste.nimi FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id INNER JOIN kategoria ON palvelu.kategoria = kategoria.id INNER JOIN tyontekija ON saatavilla.tyontekija = tyontekija.id INNER JOIN toimipiste ON saatavilla.toimipiste = toimipiste.id WHERE tyontekija = :1 AND kategoria = :2 AND palvelu = :3");
		$sql->bindValue(':1', $tyontekija);
		$sql->bindValue(':2', $kategoria);
		$sql->bindValue(':3', $palvelu);
		$sql->execute();
		//Jos tuloksia löytyi, viedään ne toimipisteselectiin.
		if ($sql->rowCount() > 0) {
			echo "<option selected='selected' value='poistettu' disabled >Valitse toimipiste</option>";
			while ($row = $sql->fetch()) {
				
				echo "<option value='".$row["toimipiste"]."'>".$row["nimi"]."</option>";

			}
		}
		//Jos tuloksia ei löytynyt, tiedotetaan siitä.
		else {
			echo "<option value='noresults'>Ei tuloksia</option>";
		}
		//Lopuksi katkaistaan tietokantayhteys.
		$conn = null;
	}
}
//Jos arvoja ei ole, näytetään virheilmoitus.
else {
	echo "errorfile";
}
?>