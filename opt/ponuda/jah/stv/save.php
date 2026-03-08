<?

include_once("opt/ponuda/ponuda.class.php");

	
	
	
	$rowold=new simPonudaStavka($database);
	$rowold->load($id);
	
	$row=new simPonudaStavka($database);
	$row->bind($_POST);
	$isOld=intval($row->id);
	$row->check($_POST);
	$row->store();
	
	$database->execQuery("SELECT @i:=0");
	$database->execQuery("UPDATE ponuda_stavka SET ordering=@i:=@i+1 WHERE ponudaID=$ponudaID ORDER BY ordering,id DESC");
	
	$rac=new simPonuda($database);
	$rac->id=$row->ponudaID;
	
	$rac->calculateIznos(' €',true);
	
	$rac->setJavascript($res);
	
	if(intval($row->ponudaID)>0) {
		
		$rac->load($row->ponudaID);
		
		
		$rac->modifiedByID=$myID;
		$rac->modified=date("Y-m-d H:i:s");
		$database->execQuery("UPDATE ponuda SET modifiedByID=$myID,modified=NOW() WHERE id=".$rac->id);
		
	
		if($pageopt=='ponuda') {
			$rac->removeSufix();
			$rac->convertAllDatesToHR();
			$rac->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$rac->userID));
			
			$res->changeRowValues('tbl_ponuda',$rac,$_SESSION['tbl_ponuda_fields']);
				//$res->markRow("tbl_ponuda",$row->id,$col);
			
		} 
	}
	$LOG->savelogNOW("Izmjena ponude",$rac->naziv." [".$rac->code."]",$rac->export("NOVI PODACI:"));
//	$res->javascript('tbl_pstavke.clearAll();tbl_pstavke.init();
//	tbl_pstavke.loadXML("ajx.php?opt=ponuda&act=stavke&id='.$rac->id.'")');
	$res->javascript("loadTable(tbl_pstavke,'opt=ponuda&act=stavke&id=".$rac->id."',".intval($database->getResult("SELECT COUNT(id FROM ponuda_stavka WHERE ponudaID=".$rac->id)).",true);");
	$res->closeSimpleDialog(3);
	$res->alertOK();

?>