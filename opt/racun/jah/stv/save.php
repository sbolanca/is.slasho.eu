<?

include_once("opt/racun/racun.class.php");

	
	
	
	$rowold=new simRacunStavka($database);
	$rowold->load($id);
	
	$row=new simRacunStavka($database);
	$row->bind($_POST);
	$isOld=intval($row->id);
	$row->check($_POST);
	$row->store();
	
	$database->execQuery("SELECT @i:=0");
	$database->execQuery("UPDATE racun_stavka SET ordering=@i:=@i+1 WHERE racunID=$racunID ORDER BY ordering,id DESC");
	
	$rac=new simRacun($database);
	$rac->id=$row->racunID;
	
	$rac->calculateIznos(' €',true);
	
	$rac->setJavascript($res);
	
	$res->closeSimpleDialog(3);
	
	if(intval($row->racunID)>0) {
		
		$rac->load($row->racunID);
		
		
		$rac->modifiedByID=$myID;
		$rac->modified=date("Y-m-d H:i:s");
		$database->execQuery("UPDATE racun SET modifiedByID=$myID,modified=NOW() WHERE id=".$rac->id);
		
	
		if($pageopt=='racun') {
			$rac->removeSufix();
			$rac->convertAllDatesToHR();
			$rac->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$rac->userID));
			
			$res->changeRowValues('tbl_racun',$rac,$_SESSION['tbl_racun_fields']);
				//$res->markRow("tbl_racun",$row->id,$col);
			
		} 
	}
	$LOG->savelogNOW("Izmjena računa",$rac->naziv." [".$rac->code."]",$rac->export("NOVI PODACI:"));
//	$res->javascript('tbl_stavke.clearAll();tbl_stavke.init();
//	tbl_stavke.loadXML("ajx.php?opt=racun&act=stavke&id='.$rac->id.'")');
	$res->javascript("loadTable(tbl_stavke,'opt=racun&act=stavke&id=".$rac->id."',".intval($database->getResult("SELECT COUNT(id FROM racun_stavka WHERE racunID=".$rac->id)).",true);");
	$res->closeSimpleDialog(3);
	$res->alertOK();

?>