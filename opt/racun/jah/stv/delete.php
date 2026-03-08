<?

include_once("opt/racun/racun.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$racunID=intval(simGetParam($_REQUEST,'racunID',$id));

	$rac=new simRacun($database);
	$rac->load($racunID);

	$database->setQuery("SELECT * FROM racun_stavka WHERE id IN (".$ids.")");
	$rows=$database->loadObjectList('id');
	
	clearTable("racun_stavka",'id',$ids);
	
	foreach($rows as $ix=>$row) {
		$LOG->savelogNOW("Brisanje stavke računa",$row->opis,$row->id,$ix);
		$res->deleteRow("tbl_stavke",$ix);
	}
	$database->execQuery("SELECT @i:=0");
	$database->execQuery("UPDATE racun_stavka SET ordering=@i:=@i+1 WHERE racunID=$racunID ORDER BY ordering,id DESC");
	
	
	$rac->calculateIznos(' kn',true);
	$rac->setJavascript($res);
	
	if($racunID>0) {
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
	$res->javascript("loadTable(tbl_stavke,'opt=racun&act=stavke&id=".$rac->id."',"
	.intval($database->getResult("SELECT COUNT(id FROM racun_stavka WHERE racunID=".$rac->id)).",true);");
	
	
?>