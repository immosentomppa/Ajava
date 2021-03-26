 <?php
include('connect.php');
//Jos kategoria on asetettu ja se postataan -> asetetaan se muuttujaan.
if (isset($_POST['kategoria']) && $_POST['kategoria']) {
	$kategoria = $_POST['kategoria']; 
	//Haetaan palvelut halutulla kategorialla.
	$sql = $conn->prepare("SELECT palvelu.id, palvelu.nimi FROM palvelu WHERE palvelu.kategoria = :1");
	$sql->bindValue(':1', $kategoria);
	$sql->execute();
	//Jos tuloksia on, viedään ne palveluselectille.
	if ($sql->rowCount() > 0) {
		echo "<option selected='selected' value='poistettu' disabled >Valitse palvelu</option>";
		while ($row = $sql->fetch()) {
			echo "<option value='".$row["id"]."'>".$row["nimi"]."</option>";
		}
	}
	//Jos tuloksia ei ole, tiedotetaan siitä.
	else
	{
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