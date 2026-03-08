<?

include("opt/kategorijaslike/kategorijaslike.class.php");




$row=new simKategorijaSlike($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->check(true);
if(!$isOld) $row->setNextOrdering();

	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena kategorije slike":"Nova kategorija slike",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='kategorijaslike') {
		if($isOld) $res->changeRowValues('tbl_kategorijaslike',$row,$_SESSION['tbl_kategorijaslike_fields']);
		else	$res->addRow('tbl_kategorijaslike',$row,$_SESSION['tbl_kategorijaslike_fields']);
	}		


?>