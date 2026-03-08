<?

include("opt/klijent/klijent.class.php");




$row=new simKlijent($database);
$row->bind($_POST);

$isOld=intval($row->id);
if(!$row->puni_naziv) $row->puni_naziv=$row->naziv;
else if(!$row->naziv) $row->naziv=$row->puni_naziv;
$row->servis=intval(simGetParam($_REQUEST,'servis',0));
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena klijenta":"Novi klijent",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='klijent') {
		if($isOld) $res->changeRowValues('tbl_klijent',$row,$_SESSION['tbl_klijent_fields']);
		else	$res->addRow('tbl_klijent',$row,$_SESSION['tbl_klijent_fields'],'0');
	}


?>