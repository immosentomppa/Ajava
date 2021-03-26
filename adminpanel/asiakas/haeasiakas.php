<?
//Jos asiakkaan etunimi ja sukunimi on asetettu ja ne postataan -> asetetaan ne muuttujiin.
if (isset($_POST['etunimi'], $_POST['sukunimi']) && $_POST['etunimi'] && $_POST['sukunimi'] ){
	include('../../connect.php');
	$etunimi = $_POST['etunimi'];
	$sukunimi = $_POST['sukunimi'];
	try {
		//Haetaan asiakkaat annetuilla tiedoilla ja tehdään tiedoista taulukko.
		$query_find = $conn->prepare("SELECT etunimi, sukunimi, puhnum, sposti FROM asiakas WHERE etunimi = :1 AND sukunimi = :2");
		$query_find->bindValue(':1', $etunimi);
		$query_find->bindValue(':2', $sukunimi);
		$query_find->execute();
		if ($query_find->rowCount() > 0) {
			echo '<h2 class="text-left smaller-margintop">Asiakkaat</h2>
				<div class="rullatable">
				<table class="table-bordered table-marginbottom">
				<thead>
				<tr>
				<th>Etunimi</th>
				<th>Sukunimi</th>
				<th>Puhelinnumero</th>
				<th>Sähköpostiosoite</th>
				</thead>
				</tr><tbody>';
			while ($row = $query_find->fetch()) {
				echo '<tr>
					<td>'.$row['etunimi'].'</td>
					<td>'.$row['sukunimi'].'</td>
					<td>'.$row['puhnum'].'</td>
					<td>'.$row['sposti'].'</td>
					</tr>';
				
				
			}
			echo '</tbody></table></div>';
		}
		//Jos asiakkaita ei ole, tiedotetaan siitä.
		else {
			echo "eiole";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos suorittamisessa ilmenee ongelma, käsketään yrittämään uudestaan.
	catch (PDOException $e) {
		echo "tryagain";
	}
}
//Jos tietoja ei ole, käsketään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>