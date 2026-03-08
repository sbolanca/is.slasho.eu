<?

include("opt/specijalniupit/specijalniupit.class.php");




$row=new simSpecijalniUpit($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena specijalnog upita":"Novi specijalni upit",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='specijalniupit') {
		if($isOld) $res->changeRowValues('tbl_specijalniupit',$row,$_SESSION['tbl_specijalniupit_fields']);
		else	$res->addRow('tbl_specijalniupit',$row,$_SESSION['tbl_specijalniupit_fields'],'0');
	}		


?>