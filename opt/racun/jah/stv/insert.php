<?


include_once("opt/racun/racun.class.php");
		
		$defaultPDV=intval(getConfig('UPDV'));
		
		$blank=array();
		$flds=simGetParam($_REQUEST,'selectItemfld',$blank);
		$lastOrd=intval($database->getResult("SELECT MAX(ordering) FROM racun_stavka WHERE racunID=$id"));
		$database->execQuery("SELECT @i:=$lastOrd");
		foreach($flds as $folderID) {
			if($folderID==-1) {
				$sessClipboard=trim(simGetParam($_SESSION,'sta_clipboard',''));
				if($sessClipboard) {
					
					$database->execQuery("INSERT INTO racun_stavka (ordering,racunID,stavkaID,opis,mjera,kolicina,cijena,stopa_pdv)"
				." SELECT @i:=@i+1 as ord,'$id' as rid,id,IF(code='',naziv,CONCAT('[',code,'] ',naziv)) as naz,mjera,'1' as kol,iznos,IF($defaultPDV,stopa_pdv,0) as stpdv FROM stavka WHERE id IN ($sessClipboard)");
				}
			} else {
				$database->execQuery("INSERT INTO racun_stavka (ordering,racunID,stavkaID,opis,mjera,kolicina,cijena,stopa_pdv)"
				." SELECT @i:=@i+1 as ord,'$id' as sid,s.id,IF(code='',naziv,CONCAT('[',code,'] ',naziv)) as naz,s.mjera,'1' as kol,s.iznos,IF($defaultPDV,s.stopa_pdv,0) as stpdv FROM sta_folder_stavka AS f"
				." LEFT JOIN stavka AS s ON s.id=f.stavkaID"
				." WHERE f.folderID=$folderID");
			
				
			}
		}
	
	
	$res->alertOK();
	
	$rac=new simRacun($database);
	$rac->id=$id;
	
	$rac->calculateIznos(' €',true);
	
	$rac->setJavascript($res);
	
	if(intval($id)>0) {
		
		$rac->load($id);
		
		
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
		$LOG->savelogNOW("Izmjena računa",$rac->naziv." [".$rac->code."]",$rac->export("NOVI PODACI:"));

	}
//	$res->javascript('tbl_stavke.clearAll();tbl_stavke.init();
//	tbl_stavke.loadXML("ajx.php?opt=racun&act=stavke&id='.$rac->id.'")');
	$res->javascript("loadTable(tbl_stavke,'opt=racun&act=stavke&id=".$rac->id."',".intval($database->getResult("SELECT COUNT(id FROM racun_stavka WHERE racunID=".$rac->id)).",true);");
	


?>