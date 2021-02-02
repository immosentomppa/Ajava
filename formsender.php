<?php
//Jos etunimi, sukunimi ja puhelinnumero on asetettu ja ne postataan
if (isset($_POST['enimi'], $_POST['snimi'], $_POST['puhnum']) && $_POST['enimi'] && $_POST['snimi'] && $_POST['puhnum']) 
{
	//Asetetaan aikavyöhyke ja postatut arvot muuttujiin.
	date_default_timezone_set("Europe/Helsinki");
	$paivamaara_raaka = $_POST['paivamaaraValue'];
	$paivamaaraText = $_POST['paivamaaraText'];
	$tyontekijaText = $_POST['tyontekijaText'];
	$kategoriaText = $_POST['kategoriaText'];
	$palveluText = $_POST['palveluText'];
	$toimipisteText = $_POST['toimipisteText'];
	$etunimi = $_POST['enimi'];
	$sukunimi = $_POST['snimi'];
	$puhnum = $_POST['puhnum'];
	$kategoria = $_POST['kategoriaValue'];
	$palvelu = $_POST['palveluValue'];
	$tyontekija = $_POST['tyontekijaValue'];
	$toimipiste = $_POST['toimipisteValue'];
	$aika = $_POST['aikaValue'];
	$saatavillaID = $_POST['saatavillaID'];
	//Jos käyttäjä on valinnut sähköpostimuistutuksen, asetetaan sähköposti muuttujaan.
	if (isset($_POST['muistutuslaatikko']) && $_POST['muistutuslaatikko'] == 'valittu') {
		$sposti = $_POST['sposti'];
	}
	//Muutoin asetetaan sähköposti tyhjäksi.
	else 
	{
		$sposti = "";
	}
	//Jos lisätietokenttä on tyhjä, asetetaan senkin muuttuja tyhjäksi.
	if (!strlen(trim($_POST['lisatiedot']))) {
		$lisatiedot = "";
	}
	//Muutoin asetetaan siihen postattu arvo.
	else 
	{
		$lisatiedot = $_POST['lisatiedot'];
	}
	//Yritetään suorittaa komentoja
	try {
		include('connect.php');
		//Katsotaan, löytyykö asiakas jo tietokannasta.
		$tarkistaAsiakas = $conn->prepare("SELECT id FROM asiakas WHERE asiakas.etunimi = :1 AND asiakas.sukunimi = :2 AND asiakas.puhnum = :3");
		$tarkistaAsiakas->bindValue(':1', $etunimi);
		$tarkistaAsiakas->bindValue(':2', $sukunimi);
		$tarkistaAsiakas->bindValue(':3', $puhnum);
		$tarkistaAsiakas->execute();
		//Jos löytyy, asetetaan kyseisen asiakkaan id asiakasID:ksi.
		if ($tarkistaAsiakas->rowCount() > 0) 
		{
			while ($row_asiakas = $tarkistaAsiakas->fetch()) {
				$asiakasID = $row_asiakas['id'];
			}
			//Sitten lisätään varaus tietokantaan
			$lisaaVaraus = $conn->prepare("INSERT INTO varaus (asiakas, palvelu, tyontekija, toimipiste, saatavillaID, paivamaara, aika, aikalukujono, lisatiedot) VALUES (:1, :2, :3, :4, :5, :6, :7, :8, :9)");
			$lisaaVaraus->bindValue(':1', $asiakasID);
			$lisaaVaraus->bindValue(':2', $palvelu);
			$lisaaVaraus->bindValue(':3', $tyontekija);
			$lisaaVaraus->bindValue(':4', $toimipiste);
			$lisaaVaraus->bindValue(':5', $saatavillaID);
			$lisaaVaraus->bindValue(':6', $paivamaara_raaka);
			$lisaaVaraus->bindValue(':7', $aika);
			$lisaaVaraus->bindValue(':8', strtotime($paivamaara_raaka. " " . $aika));
			$lisaaVaraus->bindValue(':9', $lisatiedot);
			//Varauksen lisäämisen jälkeen päivitetään saatavuustilanne varatuksi.
			if ($lisaaVaraus->execute()) {
				$paivitaMaara = $conn->prepare("UPDATE saatavilla SET varattu = :1 WHERE saatavilla.id = :2");
				$paivitaMaara->bindValue(':1', "Kyllä");
				$paivitaMaara->bindValue(':2', $saatavillaID);
				if ($paivitaMaara->execute()) {
				//Jos sähköpostimuistutus on valittu, muotoillaan viesti.
					if (isset($_POST['muistutuslaatikko']) && $_POST['muistutuslaatikko'] == 'valittu') {
						//Muotoillaan viesti.
						require_once '../../../vendor/autoload.php';
						$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
						$mailer = new Swift_Mailer($transport);
						$message = (new Swift_Message('Tunnuksesi'))
						  ->setFrom(['ala-vastaa@ajava.eu' => 'Varausmuistutus'])
						  ->setTo([$sposti])
						  ->setBody("Hei,\nTässä muistutus varauksestasi.\n\nPalvelu: ".$palveluText."\nTyöntekijä: ".$tyontekijaText."\nToimipiste: ".$toimipisteText."\nPäivämäärä: ".$paivamaaraText."\nKellonaika: ".$aika."\nLisätiedot:\n".$lisatiedot."\n--------------\nYstävällisin terveisin,\nAjava")
						  ;

						// Lähetetään viesti
						$result = $mailer->send($message);
						//Jos viestin lähetys onnistuu, palautetaan tieto siitä.
						if($result) {
							echo "success";
						}
						//Jos ei onnistu, yritetään kerran uudelleen.
						else {
							require_once '../../../vendor/autoload.php';
							$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
							$mailer = new Swift_Mailer($transport);
							$message = (new Swift_Message('Varausmuistutus'))
							  ->setFrom(['ala-vastaa@ajava.eu' => 'Ajava varausmuistutus'])
							  ->setTo([$sposti])
							  ->setBody("Hei,\nTässä muistutus varauksestasi.\n\nPalvelu: ".$palveluText."\nTyöntekijä: ".$tyontekijaText."\nToimipiste: ".$toimipisteText."\nPäivämäärä: ".$paivamaaraText."\nKellonaika: ".$aika."\nLisätiedot:\n".$lisatiedot."\n--------------\nYstävällisin terveisin,\nAjava")
							  ;

							// Lähetetään viesti
							$result = $mailer->send($message);
							//Jos viestin lähetys onnistuu, palautetaan tieto siitä.
							if($result) {
								echo "success";
							}
							//Jos ei onnistu, palautetaan virhe.
							else {
								echo "errorposti";
							}
						}
						//Lopuksi katkaistaan tietokantayhteys.
						$conn = null;
					}
					//Jos sähköpostimuistutusta ei valittu, lisätään varaus tietokantaan.
					else {
						$lisaaVaraus = $conn->prepare("INSERT INTO varaus (asiakas, palvelu, tyontekija, toimipiste, saatavillaID, paivamaara, aika, aikalukujono, lisatiedot) VALUES (:1, :2, :3, :4, :5, :6, :7, :8, :9)");
						$lisaaVaraus->bindValue(':1', $asiakasID);
						$lisaaVaraus->bindValue(':2', $palvelu);
						$lisaaVaraus->bindValue(':3', $tyontekija);
						$lisaaVaraus->bindValue(':4', $toimipiste);
						$lisaaVaraus->bindValue(':5', $saatavillaID);
						$lisaaVaraus->bindValue(':6', $paivamaara_raaka);
						$lisaaVaraus->bindValue(':7', $aika);
						$lisaaVaraus->bindValue(':8', strtotime($paivamaara_raaka. " " . $aika));
						$lisaaVaraus->bindValue(':9', $lisatiedot);
						//Lopuksi päivitetään saatavuustilanne varatuksi.
						if ($lisaaVaraus->execute()) {
							$paivitaMaara = $conn->prepare("UPDATE saatavilla SET varattu = :1 WHERE saatavilla.id = :2");
							$paivitaMaara->bindValue(':1', "Kyllä");
							$paivitaMaara->bindValue(':2', $saatavillaID);
							if ($paivitaMaara->execute()) {
								echo "success";
							}
							//Jos saatavuustilanteen päivitys epäonnistuu.
							else {
								echo "errorPaivita";
							}
						}
						//Jos varauksen lisääminen epäonnistuu.
						else {
							echo "errorVarausLisaa";
						}
					}
				}
				//Jos saatavuustilanteen päivitys epäonnistuu.
				else {
					echo "errorPaivita";
				}
			}
			//Jos varauksen lisääminen epäonnistuu.
			else {
				echo "errorVarausLisaa";
			}
			//Lopuksi katkaistaan tietokantayhteys.
			$conn = null;
		}
		//Jos asiakasta ei ole vielä tietokannassa, lisätään se sinne.
		else
		{
			$lisataanAsiakas = $conn->prepare("INSERT INTO asiakas (etunimi, sukunimi, sposti, puhnum) VALUES (:1, :2, :3, :4)");
			$lisataanAsiakas->bindValue(':1', $etunimi);
			$lisataanAsiakas->bindValue(':2', $sukunimi);
			$lisataanAsiakas->bindValue(':3', $sposti);
			$lisataanAsiakas->bindValue(':4', $puhnum);
			if ($lisataanAsiakas->execute())
			{
				//Asiakkaan lisäyksen jälkeen haetaan juuri lisätyn asiakkaan id
				$tarkistaAsiakas = $conn->prepare("SELECT id FROM asiakas WHERE asiakas.etunimi = :1 AND asiakas.sukunimi = :2 AND asiakas.puhnum = :3");
				$tarkistaAsiakas->bindValue(':1', $etunimi);
				$tarkistaAsiakas->bindValue(':2', $sukunimi);
				$tarkistaAsiakas->bindValue(':3', $puhnum);
				$tarkistaAsiakas->execute();
				if ($tarkistaAsiakas->rowCount() > 0)
				{
					while ($row_asiakas = $tarkistaAsiakas->fetch()) {
						$asiakasID = $row_asiakas['id'];
					}
					//Sitten lisätään varaus tietokantaan.
					$lisaaVaraus = $conn->prepare("INSERT INTO varaus (asiakas, palvelu, tyontekija, toimipiste, saatavillaID, paivamaara, aika, aikalukujono, lisatiedot) VALUES (:1, :2, :3, :4, :5, :6, :7, :8, :9)");
					$lisaaVaraus->bindValue(':1', $asiakasID);
					$lisaaVaraus->bindValue(':2', $palvelu);
					$lisaaVaraus->bindValue(':3', $tyontekija);
					$lisaaVaraus->bindValue(':4', $toimipiste);
					$lisaaVaraus->bindValue(':5', $saatavillaID);
					$lisaaVaraus->bindValue(':6', $paivamaara_raaka);
					$lisaaVaraus->bindValue(':7', $aika);
					$lisaaVaraus->bindValue(':8', strtotime($paivamaara_raaka. " " . $aika));
					$lisaaVaraus->bindValue(':9', $lisatiedot);
					//Varauksen lisäämisen jälkeen päivitetään saatavuustilanne varatuksi.
					if ($lisaaVaraus->execute()) {
						$paivitaMaara = $conn->prepare("UPDATE saatavilla SET varattu = :1 WHERE saatavilla.id = :2");
						$paivitaMaara->bindValue(':1', "Kyllä");
						$paivitaMaara->bindValue(':2', $saatavillaID);
						if ($paivitaMaara->execute()) {
						//Jos sähköpostimuistutus on valittu, muotoillaan viesti.
							if (isset($_POST['muistutuslaatikko']) && $_POST['muistutuslaatikko'] == 'valittu') {
								//Muotoillaan viesti
								require_once '../../../vendor/autoload.php';
								$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
								$mailer = new Swift_Mailer($transport);
								$message = (new Swift_Message('Tunnuksesi'))
								  ->setFrom(['ala-vastaa@ajava.eu' => 'Varausmuistutus'])
								  ->setTo([$sposti])
								  ->setBody("Hei,\nTässä muistutus varauksestasi.\n\nPalvelu: ".$palveluText."\nTyöntekijä: ".$tyontekijaText."\nToimipiste: ".$toimipisteText."\nPäivämäärä: ".$paivamaaraText."\nKellonaika: ".$aika."\nLisätiedot:\n".$lisatiedot."\n--------------\nYstävällisin terveisin,\nAjava")
								  ;

								// Lähetetään viesti
								$result = $mailer->send($message);
								//Jos viestin lähetys onnistuu, palautetaan tieto siitä.
								if($result) {
									echo "success";
								}
								//Jos ei onnistu, yritetään kerran uudelleen.
								else {
									require_once '../../../vendor/autoload.php';
									$transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
									$mailer = new Swift_Mailer($transport);
									$message = (new Swift_Message('Varausmuistutus'))
									  ->setFrom(['ala-vastaa@ajava.eu' => 'Ajava varausmuistutus'])
									  ->setTo([$sposti])
									  ->setBody("Hei,\nTässä muistutus varauksestasi.\n\nPalvelu: ".$palveluText."\nTyöntekijä: ".$tyontekijaText."\nToimipiste: ".$toimipisteText."\nPäivämäärä: ".$paivamaaraText."\nKellonaika: ".$aika."\nLisätiedot:\n".$lisatiedot."\n--------------\nYstävällisin terveisin,\nAjava")
									  ;

									// Lähetetään viesti
									$result = $mailer->send($message);
									//Jos viestin lähetys onnistuu, palautetaan tieto siitä.
									if($result) {
										echo "success";
									}
									//Jos ei onnistu, palautetaan virhe.
									else {
										echo "errorposti";
									}
								}
								//Lopuksi katkaistaan tietokantayhteys.
								$conn = null;
							}
							//Jos varausmuistutusta ei ole valittu, lisätään varaus tietokantaan.
							else {
								$lisaaVaraus = $conn->prepare("INSERT INTO varaus (asiakas, palvelu, tyontekija, toimipiste, saatavillaID, paivamaara, aika, aikalukujono, lisatiedot) VALUES (:1, :2, :3, :4, :5, :6, :7, :8, :9)");
								$lisaaVaraus->bindValue(':1', $asiakasID);
								$lisaaVaraus->bindValue(':2', $palvelu);
								$lisaaVaraus->bindValue(':3', $tyontekija);
								$lisaaVaraus->bindValue(':4', $toimipiste);
								$lisaaVaraus->bindValue(':5', $saatavillaID);
								$lisaaVaraus->bindValue(':6', $paivamaara_raaka);
								$lisaaVaraus->bindValue(':7', $aika);
								$lisaaVaraus->bindValue(':8', strtotime($paivamaara_raaka. " " . $aika));
								$lisaaVaraus->bindValue(':9', $lisatiedot);
								if ($lisaaVaraus->execute()) {
									//Lopuksi vielä päivitetään saatavuustilanne varatuksi.
									$paivitaMaara = $conn->prepare("UPDATE saatavilla SET varattu = :1 WHERE saatavilla.id = :2");
									$paivitaMaara->bindValue(':1', "Kyllä");
									$paivitaMaara->bindValue(':2', $saatavillaID);
									if ($paivitaMaara->execute()) {
										echo "success";
									}
									//Jos saatavuustilanteen päivitys epäonnistuu.
									else {
										echo "errorPaivita";
									}
								}
								//Jos varauksen lisäys epäonnistuu
								else {
									echo "errorVarausLisaa";
								}
								//Lopuksi katkaistaan tietokantayhteys.
								$conn = null;
							}
						}
						//Jos saatavuustilanteen päivitys epäonnistuu.
						else {
							echo "errorPaivita";
						}
					}
					//Jos varauksen lisääminen epäonnistuu.
					else {
						echo "errorVarausLisaa";
					}
				}
				//Jos asiakkaan haku tietokannasta epäonnistuu.
				else {
					echo "errorAsiakasHaku";
				}
			}
			//Jos asiakkaan lisääminen epäonnistuu.
			else {
				echo "errorAsiakasLisaa";
			}
		}

	}
	//Jos jokin kohta epäonnistuu, otetaan se talteen.
	catch(PDOException $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, tulostetaan virhe.
else {
	echo "error";
}
?>