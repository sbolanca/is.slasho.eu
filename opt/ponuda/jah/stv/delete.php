<?

include_once("opt/ponuda/ponuda.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$ponudaID=intval(simGetParam($_REQUEST,'ponudaID',$id));

	$rac=new simPonuda($database);
	$rac->load($ponudaID);

	$database->setQuery("SELECT * FROM ponuda_stavka WHERE id IN (".$ids.")");
	$rows=$database->loadObjectList('id');
	
	clearTable("ponuda_stavka",'id',$ids);
	
	foreach($rows as $ix=>$row) {
		$LOG->savelogNOW("Brisanje stavke ponude",$row->opis,$row->id,$ix);
		$res->deleteRow("tbl_pstavke",$ix);
	}
	$database->execQuery("SELECT @i:=0");
	$database->execQuery("UPDATE ponuda_stavka SET ordering=@i:=@i+1 WHERE ponudaID=$ponudaID ORDER BY ordering,id DESC");
	
	$rac->calculateIznos(' kn',true);
	$rac->setJavascript($res);

	if($ponudaID>0) {
		$rac->modifiedByID=$myID;
		$rac->modified=date("Y-m-d H:i:s");
	
		$database->execQuery("UPDATE ponuda SET modifiedByID=$myID,modified=NOW() WHERE id=".$rac->id);
		if($pageopt=='ponuda') {
				$rac->removeSufix();
				$rac->convertAllDatesToHR();
				$rac->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$rac->userID));
				
				$res->changeRowValues('tbl_ponuda',$rac,$_SESSION['tbl_ponuda_fields']);
					//$res->markRow("tbl_racun",$row->id,$col);
				
			} 
	}
	
	$res->javascript("loadTable(tbl_pstavke,'opt=ponuda&act=stavke&id=".$rac->id."',"
	.intval($database->getResult("SELECT COUNT(id FROM ponuda_stavka WHERE ponudaID=".$rac->id)).",true);");
	
	
?>