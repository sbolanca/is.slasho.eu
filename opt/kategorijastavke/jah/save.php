<?

include("opt/kategorijastavke/kategorijastavke.class.php");




$row=new simKategorijaStavke($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena kategorije stavke":"Nova kategorija stavke",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='kategorijastavke') {
		if($isOld) $res->changeRowValues('tbl_kategorijastavke',$row,$_SESSION['tbl_kategorijastavke_fields']);
		else	$res->addRow('tbl_kategorijastavke',$row,$_SESSION['tbl_kategorijastavke_fields'],'0');
	}		


?>