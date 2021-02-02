<?php
session_start();
//Jos käyttäjätunnus ja salasana on asetettu ja ne postataan -> asetetaan ne muuttujiin
if (isset($_POST['kayttajatunnus'], $_POST['salasana']) && $_POST['kayttajatunnus'] && $_POST['salasana']) {
	try {
		$kayttajatunnus = $_POST['kayttajatunnus'];
		$rooliValue = "";
		$salasana_raaka = $_POST['salasana'];
		$pysykirjautuneena = $_POST['pysykirjautuneena'];
		include('../connect.php');
		//Haetaan tietokannasta hashattu salasana käyttäjätunnuksen perusteella.
		$varmistaSalis = $conn->prepare("SELECT salasana FROM kayttajat WHERE kayttajatunnus = :1");
		$varmistaSalis->bindValue(':1', $kayttajatunnus);
		$varmistaSalis->execute();
		//Jos salasana löytyy, tallennetaan se muuttujaan.
		if ($varmistaSalis->rowCount() > 0) {
			while ($rivi = $varmistaSalis->fetch()) {
				$hashattuSalis = $rivi['salasana'];
			}
			// Katsotaan onko käyttäjä admin vai ei
			$rooliQuery = $conn->prepare("SELECT role FROM kayttajat WHERE kayttajatunnus = :1");
			$rooliQuery->bindValue(':1', $kayttajatunnus);
			$rooliQuery->execute();
			if ($rooliQuery->rowCount() > 0) {
				while ($rivi = $rooliQuery->fetch()) {
					$rooliValue = $rivi['role'];
				}
			}
			$conn = null;
			/*Jos salasana täsmää tietokannassa olevaan hashattuun, tarkistetaan käyttäjätunnus ja asetetaan session
			  sen perusteella. Samalla tarkistetaan myös, haluaako käyttäjä pysyä kirjautuneena. */
			if (password_verify($salasana_raaka, $hashattuSalis)) {
				if ($rooliValue == "admin") {
					if (isset($_POST['pysykirjautuneena']) && $_POST['pysykirjautuneena']) {
						$_SESSION['kayttaja'] = "admin";
						$_SESSION['kayttajatunnus'] = "admin";
						$_SESSION['pysykirjautuneena'] = true;
						echo "success";
					}
					else {
						$_SESSION['kayttaja'] = "admin";
						$_SESSION['kayttajatunnus'] = "admin";
						echo "success";
					}
				} else {
					if (isset($_POST['pysykirjautuneena']) && $_POST['pysykirjautuneena']) {
						$_SESSION['kayttaja'] = "normaali";
						$_SESSION['kayttajatunnus'] = $kayttajatunnus;
						$_SESSION['pysykirjautuneena'] = true;
						echo "success";
					}
					else {
						$_SESSION['kayttaja'] = "normaali";
						$_SESSION['kayttajatunnus'] = $kayttajatunnus;
						echo "success";
					}
				}
			}
			//Jos salasana ei täsmää, tiedotetaan siitä.
			else {
				echo "wrongdetails";
			}
		}
		else {
			echo "tryagain";
		}
	}
	//Jos jokin epäonnistuu, pyydetään yrittämään uudelleen.
	catch (Exception $e) {
		echo "tryagain";
	}
}
//Jos arvoja ei ole, pyydetään yrittämään uudelleen.
else {
	echo "tryagain";
}
?>