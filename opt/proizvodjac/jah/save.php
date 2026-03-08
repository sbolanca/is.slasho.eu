<?

include("opt/proizvodjac/proizvodjac.class.php");




$row=new simProizvodjac($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena proizvođača":"Novi proizvođač",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='proizvodjac') {
		if($isOld) $res->changeRowValues('tbl_proizvodjac',$row,$_SESSION['tbl_proizvodjac_fields']);
		else	$res->addRow('tbl_proizvodjac',$row,$_SESSION['tbl_proizvodjac_fields'],'0');
	}		


?>