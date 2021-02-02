<?php
//Jos ajan tiedot on asetettu ja ne postataan, asetetaan ne muuttujiin
if (isset($_POST['saatavuuspalvelu'], $_POST['saatavuustoimipiste'], $_POST['saatavuustyontekija'], 
$_POST['saatavuuspaivamaara'], $_POST['saatavuusaika']) && $_POST['saatavuuspalvelu'] && $_POST['saatavuustoimipiste'] 
&& $_POST['saatavuustyontekija'] && $_POST['saatavuuspaivamaara'] && $_POST['saatavuusaika']) {
	$palvelu = $_POST['saatavuuspalvelu'];
	$toimipiste = $_POST['saatavuustoimipiste'];
	$tyontekija = $_POST['saatavuustyontekija'];
	$date = date_create($_POST['saatavuuspaivamaara']);
	$paivamaara = date_format($date, "Y-m-d");
	$aika = $_POST['saatavuusaika'];
	include('../../connect.php');
	try {
		//Jos aika on vähintään 2 tunnin päässä, tarkistetaan aluksi onko samalla työntekijällä samaan aikaan toista aikaa.
		if (strtotime($paivamaara. " ". $aika) > strtotime('+2 hours')) {
			
			$query_find = $conn->prepare("SELECT * FROM saatavilla WHERE palvelu = :1 AND toimipiste = :2 AND tyontekija = :3 AND paivamaara = :4 AND aika = :5");
			$query_find->bindValue(':1', $palvelu);
			$query_find->bindValue(':2', $toimipiste);
			$query_find->bindValue(':3', $tyontekija);
			$query_find->bindValue(':4', $paivamaara);
			$query_find->bindValue(':5', $aika);
			$query_find->execute();
			//Jos on, tiedotetaan siitä.
			if ($query_find->rowCount() > 0) {
				echo "onjo_error";
			}
			//Jos ei ole, haetaan kyseisen työntekijän muut ajat samana päivänä.
			else if ($query_find->rowCount() == 0) {
				$query_onkovapaa = $conn->prepare("SELECT saatavilla.loppuu as loppuu, saatavilla.aika as alkaa, palvelu.kesto as palvelukesto FROM saatavilla INNER JOIN palvelu ON saatavilla.palvelu = palvelu.id WHERE tyontekija = :1 AND paivamaara = :2");
				$query_onkovapaa->bindValue(':1', $tyontekija);
				$query_onkovapaa->bindValue(':2', $paivamaara);
				$query_onkovapaa->execute();
				if ($query_onkovapaa->rowCount() > 0) {
					while ($row = $query_onkovapaa->fetch()) {
						$loppuu_temp = $row['loppuu'];
						$kesto = $row['palvelukesto'];
						$alkuaika_temp = $row['alkaa'];
					}
					//Muotoillaan ajat tarvittavaan muotoon.
					$loppuujono = strtotime($loppuu_temp);
					$loppuulyhyt = date("H:i", $loppuujono);
					$loppuu = date_format(date_create($loppuulyhyt), "H:i");
					$alkaajono = strtotime($alkuaika_temp);
					$alkaalyhyt = date("H:i", $alkaajono);
					$alkuaika = date_format(date_create($alkaalyhyt), "H:i");
					$inputalku = date_format(date_create($aika), "H:i");
					$inputloppu = date("H:i", strtotime($aika + $kesto));
					$onjo = 0;
					//Ja sitten tarkistetaan, onko työntekijällä vapaata koko halutun ajan.
					if ($inputalku >= $alkuaika && $inputloppu <= $loppuu) {
						echo "onjo_error";
						$onjo = 1;
					}
					else if ($inputalku <= $alkuaika && $inputloppu > $alkuaika && $inputloppu < $loppuu) {
						echo "onjo_error";
						$onjo = 1;
					}
					else if ($inputalku >= $alkuaika && $inputalku < $loppuu && $inputloppu > $loppuu) {
						echo "onjo_error";
						$onjo = 1;
					}
					//Jos on, lisätään aika tietokantaan.
					else if ($onjo == 0) {
						$query_add = $conn->prepare("INSERT INTO saatavilla (palvelu, tyontekija, toimipiste, paivamaara, aika, loppuu, aikalukujono, varattu) VALUES (:1, :2, :3, :4, :5, :6, :7, :8)");
						$query_add->bindValue(':1', $palvelu);
						$query_add->bindValue(':2', $tyontekija);
						$query_add->bindValue(':3', $toimipiste);
						$query_add->bindValue(':4', $paivamaara);
						$query_add->bindValue(':5', $aika);
						$query_add->bindValue(':6', $inputloppu);
						$query_add->bindValue(':7', strtotime($paivamaara ." ". $aika));
						$query_add->bindValue(':8', "Ei");
						//Jos tietokantaan lisääminen onnistui, palautetaan tieto siitä.
						if ($query_add->execute()) {
							echo "onnistui";
						}
						//Muussa tapauksessa pydetään yrittämään uudelleen.
						else {
							echo "tryagain1";
						}
					}
				}
				else {
					$query_haekesto = $conn->prepare("SELECT palvelu.kesto FROM palvelu WHERE palvelu.id = :1");
					$query_haekesto->bindValue(':1', $palvelu);
					$query_haekesto->execute();
					if ($query_haekesto->rowCount() > 0) {
						while ($row = $query_haekesto->fetch()) {
							$kesto = $row['kesto'];
						}
						$inputloppu = date("H:i", strtotime($aika + $kesto));
						$query_add = $conn->prepare("INSERT INTO saatavilla (palvelu, tyontekija, toimipiste, paivamaara, aika, loppuu, aikalukujono, varattu) VALUES (:1, :2, :3, :4, :5, :6, :7, :8)");
						$query_add->bindValue(':1', $palvelu);
						$query_add->bindValue(':2', $tyontekija);
						$query_add->bindValue(':3', $toimipiste);
						$query_add->bindValue(':4', $paivamaara);
						$query_add->bindValue(':5', $aika);
						$query_add->bindValue(':6', $inputloppu);
						$query_add->bindValue(':7', strtotime($paivamaara ." ". $aika));
						$query_add->bindValue(':8', "Ei");
						//Jos tietokantaan lisääminen onnistui, palautetaan tieto siitä.
						if ($query_add->execute()) {
							echo "onnistui2";
						}
						//Muussa tapauksessa pydetään yrittämään uudelleen.
						else {
							echo "tryagain2";
						}
					}
					else {
						echo "tryagain5";
					}

				}
			}
		}
		//Jos aika ei ole 2 tunnin päässä, palautetaan tieto siitä.
		else {
			echo "liianaikaisin";
		}
		//Lopuksi tuhotaan tietokantayhteys.
		$conn = null;
	}
	//Jos jokin kohta epäonnistuu, pyydetään yrittämään uudestaan.
	catch (PDOException $e) {
		echo $e;
		echo "tryagain3";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain4";
}
?>