<?

include_once("opt/racun/racun.class.php");

	
	$rowold=new simRacun($database);
	$rowold->load($id);
	
	$row=new simRacun($database);
	$row->bind($_POST);
	
	$delim=getConfig('RacunDelimiter');
	
	$isOld=intval($row->id)>0?true:false;
	if (!$isOld) {
		$tempID=intval($row->id);
		$row->createdByID=$myID;
		$row->created=date("Y-m-d H:i:s");
		$next_racun=intval(getConfig('next_racun'));
		$code=$next_racun.$delim."1".$delim."1";
		$database->execQuery("UPDATE configuration SET value=GREATEST(value*1,1+$next_racun) WHERE type='next_racun'");
		if(!($code==($row->code.$delim."1".$delim."1"))) $res->alert("U međuvremenu je došlo do promjene slijedećeg broja računa.\n\nNovi broj vašeg računa je $code.");
		$row->code=$code;

		$row->id=0;
	} else {
		$tempID=0;
		$row->code.=$delim."1".$delim."1";
	}
	$row->modifiedByID=$myID;
	$row->modified=date("Y-m-d H:i:s");
	
	if(!$row->vrijeme_izdavanja) $row->vrijeme_izdavanja='NULL';
	if(!$row->datum_izdavanja) $row->datum_izdavanja='NULL'; else $row->convertDateToSQL('datum_izdavanja');
	if(!$row->datum_isporuke) $row->datum_isporuke='NULL'; else $row->convertDateToSQL('datum_isporuke');
	if(!$row->datum_dospijeca) $row->datum_dospijeca='NULL'; else $row->convertDateToSQL('datum_dospijeca');
	
	
	
	
	$row->check(true);
	$row->store();
	$id=$row->id;
	
	if(!$isOld) $database->execQuery("UPDATE racun_stavka SET racunID=".$row->id." WHERE racunID=$tempID");
	
	$row=new simRacun($database);
	$row->load($id);
	
		$row->calculateIznos('',true);
		$row->convertAllDatesToHR();
		
		$row->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$row->userID));

	
	if($pageopt=='racun') {
	
	
		
		if (!$isOld) {
			$res->addRow('tbl_racun',$row,$_SESSION['tbl_racun_fields'],'0');			
			$LOG->savelogNOW("Novi račun",$row->naziv." [".$row->code."]",$row->export("PODACI:"));
		} else {
			$res->changeRowValues('tbl_racun',$row,$_SESSION['tbl_racun_fields']);
			//$res->markRow("tbl_racun",$row->id,$col);
			$LOG->savelogNOW("Izmjena računa",$row->naziv." [".$row->code."]",$rowold->export("STARI PODACI:").$row->export("NOVI PODACI:"));
		}
		//$res->rowCM("tbl_racun",$row->id,$row->klijentID.",".$row->status);
	}
	$res->alertOK();
	
	$res->closeSimpleDialog();
	
	

?>