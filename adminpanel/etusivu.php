			<div class="kategoriadiv text-left">
			<!--Toivotetaan käyttäjä tervetulleeksi-->
			<h2>Tervetuloa, <?php echo $_SESSION['kayttajatunnus'];?></h2>
			<?php
			//Asetetaan lokalisoinnit ja päivämäärän asettelut ym. kuntoon, jonka jälkeen tulostetaan ne.
			date_default_timezone_set('Europe/Helsinki');
			setlocale(LC_TIME, array('fi_FI.UTF-8', 'fi.UTF-8'));
			$kuukausi = strftime('%A, %d. %Bta %Y');
			$kello = strftime('%H:%M');

			?>
			<p>Tänään on <?php echo $kuukausi ?> ja kello on <?php echo $kello?></p>
			<?php
			//Haetaan varausten lukumäärä. Jos niitä on vähintään 1, näytetään nappi jolla pääsee varauksiin.
			include('connect.php');
			$sql = $conn->prepare("SELECT * FROM varaus WHERE varaus.aikalukujono > :1");
			$sql->bindValue(':1', time());
			$sql->execute();
			if ($sql->rowCount() > 0) {
				$varauksienMaara = $sql->rowCount();
				echo '<br><div>';
				echo '<h4 class="etusivuh4">Odottavia varauksia: </h4><a class="btn-success etusivubtn btn-sm" href="https://ajava.eu/adminpanel/varaukset/">'. $varauksienMaara .'</a>';
				echo '</div>';
			}
			//Jos niitä on 0, näytetään pelkkä numero. Sen jälkeen tuhotaan yhteys.
			else {
				echo '<br><h4>Odottavia varauksia: 0</h4>';
			}
			$conn = null;
			?>		
			</div>
			</div>
		</div>
	</div>
</div>
<br>
<div id="footer" class="text-center">
<p>&copy; Ajava 2018</p>
</div>