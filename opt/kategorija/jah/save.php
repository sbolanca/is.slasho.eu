<?

include("opt/kategorija/kategorija.class.php");




$row=new simKategorija($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena kategorije":"Nova kategorija",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='kategorija') {
		if($isOld) $res->changeRowValues('tbl_kategorija',$row,$_SESSION['tbl_kategorija_fields']);
		else	$res->addRow('tbl_kategorija',$row,$_SESSION['tbl_kategorija_fields'],'0');
	}		


?>