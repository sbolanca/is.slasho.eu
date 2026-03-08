<?

include("opt/racun/racun.class.php");




$row=new simRacunNapomena($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena napomene računa":"Nova napomena računa",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='racun') {
		if($isOld) $res->changeRowValues('tbl_racunnapomena',$row,$_SESSION['tbl_racunnapomena_fields']);
		else	$res->addRow('tbl_racunnapomena',$row,$_SESSION['tbl_racunnapomena_fields']);
	}		


?>