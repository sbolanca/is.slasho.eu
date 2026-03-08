<?

include_once("opt/ponuda/ponuda.class.php");

$delim=getConfig('RacunDelimiter');
	
	$rowold=new simPonuda($database);
	$rowold->load($id);
	
	$row=new simPonuda($database);
	$row->bind($_POST);
	$isOld=intval($row->id)>0?true:false;
	if (!$isOld) {
		$tempID=intval($row->id);
		$row->createdByID=$myID;
		$row->created=date("Y-m-d H:i:s");
		$ponuda_digits=intval(getConfig('ponuda_digits'));
		$next_ponuda=intval(getConfig('next_ponuda'));
		$code=sprintf("%0".$ponuda_digits."d",$next_ponuda).$delim.date("Y");
		if(!($code==$row->code)) $res->alert("U međuvremenu je došlo do promjene broja ponude.\n\nNovi broj vaše ponude je $code.");
		$row->code=$code;
		$database->execQuery("UPDATE configuration SET value=GREATEST(value*1,1+$next_ponuda) WHERE type='next_ponuda'");
		$row->id=0;
	} else $tempID=0;
	$row->modifiedByID=$myID;
	$row->modified=date("Y-m-d H:i:s");
	
	if(!$row->datum_izdavanja) $row->datum_izdavanja='NULL'; else $row->convertDateToSQL('datum_izdavanja');
	if(!$row->datum_dospijeca) $row->datum_dospijeca='NULL'; else $row->convertDateToSQL('datum_dospijeca');
	
	
	
	$row->check(true);
	$row->store();
	$id=$row->id;
	
	if(!$isOld) $database->execQuery("UPDATE ponuda_stavka SET ponudaID=".$row->id." WHERE ponudaID=$tempID");
	
	$row=new simPonuda($database);
	$row->load($id);
	
		$row->calculateIznos('',true);
		$row->convertAllDatesToHR();
		$row->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$row->userID));

	
	if($pageopt=='ponuda') {
	
	
		
		if (!$isOld) {
			$res->addRow('tbl_ponuda',$row,$_SESSION['tbl_ponuda_fields'],'0');			
			$LOG->savelogNOW("Novi ponuda",$row->naziv." [".$row->code."]",$row->export("PODACI:"));
		} else {
			$res->changeRowValues('tbl_ponuda',$row,$_SESSION['tbl_ponuda_fields']);
			//$res->markRow("tbl_ponuda",$row->id,$col);
			$LOG->savelogNOW("Izmjena ponude",$row->naziv." [".$row->code."]",$rowold->export("STARI PODACI:").$row->export("NOVI PODACI:"));
		}
		//$res->rowCM("tbl_ponuda",$row->id,$row->klijentID.",".$row->status);
	} 
	$res->alertOK();
	
	$res->closeSimpleDialog();
	
	

?>