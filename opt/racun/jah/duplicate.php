<?

include_once("opt/racun/racun.class.php");

$delim=getConfig('RacunDelimiter');
	
	$rowold=new simRacun($database);
	$rowold->load($id);
	
	$row=new simRacun($database);
	$row->bindObject($rowold);
	
	$row->id=0;
	$row->createdByID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->modifiedByID=0;
	$row->modified='NULL';
	$duration=intval(getConfig('RacunDospijece'));
	
	$next_racun=intval(getConfig('next_racun'));
	$row->code=$next_racun.$delim."1".$delim."1";
	
	$now=date("Y-m-d");
	$row->datum_izdavanja=$now;
	$row->datum_isporuke=$now;
	$row->datum_dospijeca=$database->getResult("SELECT DATE_ADD(NOW(),INTERVAL $duration DAY)");
	$row->vrijeme_izdavanja=date("H:i:s");
	
	//if(!$row->datum_izdavanja) $row->datum_izdavanja='NULL';
	//if(!$row->datum_dospijeca) $row->datum_dospijeca='NULL';

	$row->check(true);
	$row->store();
	$id=$row->id;
	
	$database->execQuery("INSERT INTO racun_stavka (racunID,stavkaID,ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust)"
	." SELECT '".$row->id." as ix',stavkaID,ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust"
	." FROM racun_stavka WHERE racunID=".$rowold->id);
	
	$database->execQuery("UPDATE configuration SET value=GREATEST(value*1,1+$next_racun) WHERE type='next_racun'");
		
	
	$row=new simRacun($database);
	$row->load($id);
	
		$row->calculateIznos('',true);
		$row->convertAllDatesToHR();
		$row->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$row->userID));

	
	if($pageopt=='racun') {

			$res->addRow('tbl_racun',$row,$_SESSION['tbl_racun_fields'],'0');			
			$LOG->savelogNOW("Dupliciranje računa",$row->naziv." [".$row->code."]",$row->export("PODACI:"));
				
			$res->rowCM("tbl_racun",$row->id,$row->klijentID.",".$row->status);
	} 
	$res->alertOK();
	
	$res->closeSimpleDialog();
	
	

?>