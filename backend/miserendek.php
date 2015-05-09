<?php
	$url = 'miserendek.php';
	$cim = 'Mentett miserendek';
	include('fejlec.php');
	include('mise_fvek.php');
	
	$m�d = 'lista';
	
	//melyik miserendet szeretn�nk megn�zni
	if ( isset($_GET['miserend_nev']) ) {
		$m�d = 'miserend';
		$miserend_n�v = $_GET['miserend_nev'];
	
		include('adatbazis.php');
		
		$miserendek = array();
		
		$parancs = "SELECT diak, nev FROM Miserend WHERE publikus='1' AND nev='$miserend_n�v'";
		$eredmeny = mysql_query($parancs);
		if ( mysql_num_rows($eredmeny) ) {
			while ($sor = mysql_fetch_array($eredmeny)) {
				//echo "<h1>$sor[1]</h1>";
				//$miserendek["$sor[1]"] = megnyit�s($sor[0]);
				$miserend = megnyit�s($sor[0]);
			}
		}
		
		mysql_close($kapcsolat);
	} else {
		include('adatbazis.php');
		
		$miserendek = array();
		
		$parancs = "SELECT diak, nev FROM Miserend WHERE publikus='1'";
		$eredmeny = mysql_query($parancs);
		if ( mysql_num_rows($eredmeny) ) {
			while ($sor = mysql_fetch_array($eredmeny)) {
				//echo "<h1>$sor[1]</h1>";
				$miserendek["$sor[1]"] = megnyit�s($sor[0]);
			}
		}
		
		mysql_close($kapcsolat);
	}
	
?>
<div id="tartalom">
<?php //echo '<pre>SESSION: '; print_r($_SESSION); echo '</pre>'; ?>
<?php //echo '<pre>POST: '; print_r($_POST); echo '</pre>'; ?>
<h2>Mentett miserendek</h2>
<?php if ( isset($�zenet) ) echo $�zenet; ?>

<?php if ( $m�d == 'lista' ) : ?>
	<!--<p>Lista m�d.</p>-->
	<?php foreach ( $miserendek as $miserend_n�v => $miserend ) : ?>
		<p><a href="?miserend_nev=<?php echo $miserend_n�v; ?>"><?php echo $miserend_n�v; ?></a></p>
	<?php endforeach; ?>
	
<?php elseif ( $m�d == 'miserend' ) : ?>
	<?php //echo '<pre>'; print_r($miserendek); echo '</pre>'; ?>
	<?php //foreach( $miserendek as $miserend_n�v => $miserend ) : ?>
		<h2><?php //echo $miserend_n�v; ?></h2>
		<?php foreach( $miserend as $miser�sz => $miserend_t�mb ) : ?>
		<?php if ( $miserend_t�mb != array() ) : ?>
			<p style="font-size: 80%;"><?php echo $miser�sz_nevek[$miser�sz]; ?>:</p>
			<table class="kerettel" style="width: 100%; font-size: 90%;">
			<?php foreach( $miserend_t�mb as $�nek_kulcs => $�nek ) : ?>
			<tr><td style="width: 50%;">
				<?php foreach( $�nek as $dia_kulcs => $�nek_r�sz ) : ?>
					<?php if ( is_array($�nek_r�sz) ) : ?>
						<p style="margin: 0;"><a href="<?php echo $�nek_r�sz['url']; ?>" onclick="return ujablak('<?php 
							echo $�nek_r�sz['url']; ?>');"><?php echo $�nek_r�sz['nev']; ?></a>
						</p>
							<?php $azon++; ?>
					<?php else : ?>
						<?php echo $�nek_r�sz; ?>
						</td><td>
						<?php $azon++; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</td></tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
		<?php endforeach; ?>
	<?php //endforeach; ?>
<?php endif; ?>

<?php include('lablec.php'); ?>