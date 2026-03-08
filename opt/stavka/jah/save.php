<?

include("opt/stavka/stavka.class.php");




$row=new simStavka($database);
$row->bind($_POST);

$isOld=intval($row->id);
$row->materijal=intval(simGetParam($_POST,'materijal',0));
$row->check(true);

if(intval($database->getResult("SELECT COUNT(id) FROM stavka WHERE id<>".intval($row->id)." AND naziv='".$row->naziv."'"))) $res->alert("Stavka sa tim nazivom već postoji.");
else if(trim($row->code) && intval($database->getResult("SELECT COUNT(id) FROM stavka WHERE id<>".intval($row->id)." AND code='".$row->code."'"))) $res->alert("Stavka sa tim kodom već postoji.");
else {
	
	$row->iznos=str_replace(",",".",$row->iznos);
	if(!$row->iznos) $row->iznos='NULL';
	$row->stopa_pdv=str_replace(",",".",$row->stopa_pdv);
	if(!$row->stopa_pdv) $row->stopa_pdv='NULL';
	
	$row->store();
	$res->alertOK();
	$LOG->saveIlog(1,$isOld?"Izmjena stavke":"Nova stavka",$row->naziv,$row->export(),$row->id,false);
	
	
	$res->closeSimpleDialog();
	
	if($pageopt=='stavka') {
		$row->addField("kategorija",$database->getResult("SELECT naziv FROM kategorijastavke WHERE id=".$row->kategorijaID));
		$row->addField("tip",$database->getResult("SELECT naziv FROM tipstavke WHERE id=".$row->tipID));
		if($isOld) $res->changeRowValues('tbl_stavka',$row,$_SESSION['tbl_stavka_fields']);
		else	$res->addRow('tbl_stavka',$row,$_SESSION['tbl_stavka_fields'],'0');
		if($row->materijal) $res->addRowClass("tbl_stavka",$row->id,'own');
		else $res->removeRowClass("tbl_stavka",$row->id,'own');
	}		

}
?>