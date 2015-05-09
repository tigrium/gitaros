<?php
	$url = 'miserendek.php';
	$cim = 'Mentett miserendek';
	include('fejlec.php');
	include('mise_fvek.php');
	
	$mód = 'lista';
	
	//melyik miserendet szeretnénk megnézni
	if ( isset($_GET['miserend_nev']) ) {
		$mód = 'miserend';
		$miserend_név = $_GET['miserend_nev'];
	
		include('adatbazis.php');
		
		$miserendek = array();
		
		$parancs = "SELECT diak, nev FROM Miserend WHERE publikus='1' AND nev='$miserend_név'";
		$eredmeny = mysql_query($parancs);
		if ( mysql_num_rows($eredmeny) ) {
			while ($sor = mysql_fetch_array($eredmeny)) {
				//echo "<h1>$sor[1]</h1>";
				//$miserendek["$sor[1]"] = megnyitás($sor[0]);
				$miserend = megnyitás($sor[0]);
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
				$miserendek["$sor[1]"] = megnyitás($sor[0]);
			}
		}
		
		mysql_close($kapcsolat);
	}
	
?>
<div id="tartalom">
<?php //echo '<pre>SESSION: '; print_r($_SESSION); echo '</pre>'; ?>
<?php //echo '<pre>POST: '; print_r($_POST); echo '</pre>'; ?>
<h2>Mentett miserendek</h2>
<?php if ( isset($üzenet) ) echo $üzenet; ?>

<?php if ( $mód == 'lista' ) : ?>
	<!--<p>Lista mód.</p>-->
	<?php foreach ( $miserendek as $miserend_név => $miserend ) : ?>
		<p><a href="?miserend_nev=<?php echo $miserend_név; ?>"><?php echo $miserend_név; ?></a></p>
	<?php endforeach; ?>
	
<?php elseif ( $mód == 'miserend' ) : ?>
	<?php //echo '<pre>'; print_r($miserendek); echo '</pre>'; ?>
	<?php //foreach( $miserendek as $miserend_név => $miserend ) : ?>
		<h2><?php //echo $miserend_név; ?></h2>
		<?php foreach( $miserend as $miserész => $miserend_tömb ) : ?>
		<?php if ( $miserend_tömb != array() ) : ?>
			<p style="font-size: 80%;"><?php echo $miserész_nevek[$miserész]; ?>:</p>
			<table class="kerettel" style="width: 100%; font-size: 90%;">
			<?php foreach( $miserend_tömb as $ének_kulcs => $ének ) : ?>
			<tr><td style="width: 50%;">
				<?php foreach( $ének as $dia_kulcs => $ének_rész ) : ?>
					<?php if ( is_array($ének_rész) ) : ?>
						<p style="margin: 0;"><a href="<?php echo $ének_rész['url']; ?>" onclick="return ujablak('<?php 
							echo $ének_rész['url']; ?>');"><?php echo $ének_rész['nev']; ?></a>
						</p>
							<?php $azon++; ?>
					<?php else : ?>
						<?php echo $ének_rész; ?>
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