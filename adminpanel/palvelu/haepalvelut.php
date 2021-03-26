<?php
//Jos kaikkien palveluiden näyttämisen kategoria on valittu ja se postataan -> tallennetaan se muuttujaan.
if (isset($_POST['palvelukategoria']) && $_POST['palvelukategoria']) {
	include('../../connect.php');
	try {
		//Haetaan kaikki palvelut kyseisestä kategoriasta taulukkoon.
		$palvelukategoria = $_POST['palvelukategoria'];
		$sql = $conn->prepare("SELECT nimi as palvelunimi FROM palvelu WHERE kategoria = :1");
		$sql->bindValue(':1', $palvelukategoria);
		$sql->execute();
		echo 'div class="rullatable">';
		echo '<table class="table-bordered kategoriataulukko table-marginbottom">';
		echo '<thead><tr><th>Nimi</th></tr></thead><tbody>';
		if ($sql->rowCount() > 0) {
			while ($rows = $sql->fetch()) {
				echo '<tr><td>' . $rows['palvelunimi'] . '</td></tr>';
			}
		//Jos palveluja ei ole, tiedotetaan siitä.
		} else {
			echo 'noservices';
		}
		echo '</tbody></table></div>';
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos kyselyssä ilmenee virhe, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Muussa tapauksessa pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>