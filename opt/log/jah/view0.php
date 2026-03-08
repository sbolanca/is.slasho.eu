<?
	$rowx=new simLog($database);
	$rowx->load($id);
	
	switch ($rowx->opt) {
		case 'snimka': 	$id=$rowx->dbindex;
						include('opt/snimka/jah/sni/showforum.php');
						break;	
		case 'zahtjev': $id=$rowx->dbindex;
						include('opt/zahtjev/jah/view.php');
						break;	
		default: include('opt/log/jah/view.php');
	}

?>