<?php
//Funktio, jolla generoidaan vahva, satunnaisista merkeistä koostuva salasana.
class randomPass {
	function generateRandomPassword($length = 10, $add_dashes = false, $available_sets = 'luds')
	{
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$password = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		if(!$add_dashes)
			return $password;
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while(strlen($password) > $dash_len)
		{
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	}
}
//Jos käyttäjän id on asetettu ja se postataan -> tallennetaan se muuttujaan.
if (isset($_POST['palautasalasana']) && $_POST['palautasalasana']) {
	$id = $_POST['palautasalasana'];
	
	try {
		include('../../connect.php');
		//Haetaan käyttäjän käyttäjätunnus ja sposti tietokannasta.
		$query = $conn->prepare("SELECT kayttajatunnus, sposti FROM kayttajat WHERE id = :1");
		$query->bindValue(':1', $id);
		$query->execute();
		if ($query->rowCount() > 0) {
			while ($row = $query->fetch()) {
				$kayttajatunnus = $row['kayttajatunnus'];
				$sposti = $row['sposti'];
			}
			//Generoidaan uusi vahva salasana, hashataan se ja asetetaan se käyttäjän salasanaksi tietokantaan.
			$randomPass = new randomPass();
			$salasana_raaka = $randomPass->generateRandomPassword();
			$salasana = password_hash($salasana_raaka, PASSWORD_DEFAULT);
			$query2 = $conn->prepare("UPDATE kayttajat SET salasana = :1 WHERE id = :2");
			$query2->bindValue(':1', $salasana);
			$query2->bindValue(':2', $id);
			//Jos salasanan asettaminen onnistuu, tyhjennetään tietokantayhteys ja lähetetään sähköpostiviesti käyttäjälle.
			if ($query2->execute()) {
				$conn = null;
				//Muotoillaan viesti.
				require_once '../../../../../vendor/autoload.php';

				$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
				$mailer = new Swift_Mailer($transport);
				$message = (new Swift_Message('Tunnuksesi'))
				  ->setFrom(['ala-vastaa@ajava.eu' => 'Ajava Tunnukset'])
				  ->setTo([$sposti])
				  ->setBody("Hei,\n\nTässä tunnuksesi:\n\nKäyttäjätunnus: ".$kayttajatunnus."\nSalasana: ".$salasana_raaka."\n\nYstävällisin terveisin,\nJärjestelmänvalvoja")
				  ;

				// Lähetetään viesti
				$result = $mailer->send($message);
				//Jos viestin lähetys onnistuu, palautetaan tieto siitä.
				if($result) {
					echo "success";
				}
				//Muussa tapauksessa pyydetään yrittämään uudestaan.
				else {
					echo "tryagain";
				}
			}
			//Jos käyttäjän salasanan päivittäminen ei onnistu, pyydetään yrittämään uudestaan.
			else {
				echo "tryagain";
			}
		}
		//Jos käyttäjää ei löydy, pyydetään yrittämään uudestaan.
		else {
			echo "tryagain";
		}

	}
	//Jos suorituksessa ilmenee virhe, pyydetään yrittämään uudestaan.
	catch (Exception $e) {
		echo "tryagain";
	}
}
//Jos arvoa ei ole, pyydetään yrittämään uudestaan.
else {
	echo "tryagain";
}
?>