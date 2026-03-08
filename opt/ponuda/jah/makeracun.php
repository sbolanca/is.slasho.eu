<?

include_once("opt/ponuda/ponuda.class.php");
include_once("opt/racun/racun.class.php");

$delim=getConfig('RacunDelimiter');
	
	$pon=new simPonuda($database);
	$pon->load($id);
	
	$row=new simRacun($database);
	$row->naziv=$pon->naziv;
	$row->klijentID=$pon->klijentID;
	$row->oib=$pon->oib;
	$row->adresa=$pon->adresa;
	$row->sjediste=$pon->sjediste;
	$row->podruznicaID=$pon->podruznicaID;
	$row->opis=$pon->opis;
	
	$row->createdByID=$myID;
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->modifiedByID=0;
	$row->modified='NULL';
	$row->nacin_placanja='Transakcijski račun';
	$row->napomenaID=intval(getConfig('RacunNapomenaID'));
	
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
	." FROM ponuda_stavka WHERE ponudaID=".$pon->id);
	
	$database->execQuery("UPDATE configuration SET value=GREATEST(value*1,1+$next_racun) WHERE type='next_racun'");
		
	
	$row=new simRacun($database);
	$row->load($id);
	
		$row->calculateIznos('',true);
		$row->convertAllDatesToHR();
		$row->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$row->userID));

	$LOG->savelogNOW("Kreiranje računa iz ponude",$row->naziv." [".$row->code."]",$row->export("PODACI:"));
			
	if($pageopt=='racun') {

			$res->addRow('tbl_racun',$row,$_SESSION['tbl_racun_fields'],'0');			
				
			$res->rowCM("tbl_racun",$row->id,$row->klijentID.",".$row->status);
	}  else $res->alert("Napravljen je novi račun s brojem: ".$row->code);
	$res->alertOK();


	
	

?>