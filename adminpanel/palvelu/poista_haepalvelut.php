<?php
//Haetaan kategorian palvelut palvelun poistoa varten.
if (isset($_POST['kategoria']) && $_POST['kategoria']) {
	include('../../connect.php');
	try {
		$kategoria = $_POST['kategoria'];
		$sql = $conn->prepare("SELECT nimi as palvelunimi, id as palveluid FROM palvelu WHERE kategoria = :1");
		$sql->bindValue(':1', $kategoria);
		$sql->execute();
		//Jos palveluita löytyy, palautetaan ne selectiin.
		if ($sql->rowCount() > 0) {
			echo '<option value="poistettu" disabled selected>Valitse palvelu</option>';
			while ($rows = $sql->fetch()) {
				echo "<option value='".$rows['palveluid']."'>" . $rows['palvelunimi'] . "</option>";
			}
		//Jos palveluita ei ole, palautetaan tieto siitä.
		} else {
			echo "<option value='noresults'>Ei palveluita kategoriassa</option>";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos jokin kohta epäonnistuu, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>