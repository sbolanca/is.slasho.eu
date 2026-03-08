<?

include("opt/tipstavke/tipstavke.class.php");




$row=new simTipStavke($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena tipa stavke":"Novi tip stavke",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='tipstavke') {
		if($isOld) $res->changeRowValues('tbl_tipstavke',$row,$_SESSION['tbl_tipstavke_fields']);
		else	$res->addRow('tbl_tipstavke',$row,$_SESSION['tbl_tipstavke_fields'],'0');
	}		


?>