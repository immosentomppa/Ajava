			<div class="kategoriadiv text-left">
				<h2 class="text-left kategoriaotsikko">Varatut ajat</h2><br>
				<?php
				//Näytetään kaikki varaukset taulukossa, jotka ovat tulevaisuudessa.
				include('../../connect.php');
				date_default_timezone_set("Europe/Helsinki");
				$date = date("Y-m-d");
				$time = date("H:i");
				$sql = $conn->prepare("SELECT varaus.id as id, varaus.lisatiedot as lisatiedot, varaus.paivamaara as paiva, varaus.aika, varaus.asiakas as asiakasid, asiakas.etunimi as asiakkaanetunimi, asiakas.puhnum as asiakkaanpuhnum, asiakas.sukunimi as asiakkaansukunimi, palvelu.nimi as palvelunimi, tyontekija.nimi as tyontekijanimi, toimipiste.nimi as toimipistenimi FROM varaus INNER JOIN palvelu ON varaus.palvelu = palvelu.id INNER JOIN tyontekija ON varaus.tyontekija = tyontekija.id INNER JOIN toimipiste ON varaus.toimipiste = toimipiste.id INNER JOIN asiakas ON varaus.asiakas = asiakas.id WHERE varaus.aikalukujono >= :1");
				$sql->bindValue(':1', time());
				$sql->execute();
				if ($sql->rowCount() > 0) {
					echo '<p class="text-left">Klikkaa asiakkaan numeroa avataksesi tarkemmat tiedot.</p>';
					echo '<div class="rullatable">';
					echo '<table class="table-bordered kategoriataulukko">';
					echo "<thead>
							<tr>
							<th>ID</th>
							<th>Palvelu</th>
							<th>Toimipiste</th>
							<th>Asiakas</th>
							<th>Työntekijä</th>
							<th>Päivämäärä</th>
							<th>Aika</th>
							<th>Lisätiedot</th>
							</tr>
							</thead>
							<tbody>";
					//Sen lisäksi haetaan jokaisen varauksen asiakkaan tiedot.
					while ($row = $sql->fetch()) {
						$date = new DateTime($row['paiva']);
						$oikeepaivamaara = $date->format("d.m.Y");
						$asiakkaanpuhnum = $row['asiakkaanpuhnum'];
						$asiakkaannimi = $row['asiakkaanetunimi']. " " . $row['asiakkaansukunimi'];
						echo '<tr><td>'.$row['id'].'</td><td>'.$row['palvelunimi'].'</td><td>'.$row['toimipistenimi'].'</td><td><button type="button" class="btn btn-info btn-sm btn-asiakas" data-toggle="modal" data-target="#asiakasModal">'.$row['asiakasid'].'</button></td><td>'.$row['tyontekijanimi'].'</td><td>'.$oikeepaivamaara.'</td><td>'.date("H:i", strtotime($row['aika'])).'</td><td>'.$row['lisatiedot'].'</td></tr>';
					}
					echo '</tbody>
							</table>
							<div>';
				} else {
					//Jos varauksia ei ole, ilmoitetaan siitä.
					echo "<p class='text-left'>Ei varauksia.</p>";
				}
				//Lopuksi tuhotaan yhteys
				$conn = null;
				?>
				<br>
			<div class="modal fade" id="asiakasModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
			  <div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Asiakkaan tiedot</h4>
				  </div>
				  <div class="modal-body">
				  <br>
				  <table class="table-bordered">
				  <thead>
				  <tr>
				  <th>Nimi</th>
				  <th>Puhelinnumero</th>
				  </tr>
				  </thead>
				  <tbody>
				  <tr>
				  <td><?php echo $asiakkaannimi;?></td>
				  <td><?php echo $asiakkaanpuhnum;?></td>
				  </tr>
				  </tbody>
				  </table>
				  <br>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
				  </div>
				</div>
			  </div>
			</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
<br>
<div id="footer" class="text-center">
<p>&copy; Ajava 2018</p>
</div>