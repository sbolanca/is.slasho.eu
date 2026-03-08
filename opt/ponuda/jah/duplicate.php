<?

include_once("opt/ponuda/ponuda.class.php");

$delim=getConfig('RacunDelimiter');
	
	$rowold=new simPonuda($database);
	$rowold->load($id);
	
	$row=new simPonuda($database);
	$row->bindObject($rowold);
	
	$row->id=0;
	$row->createdByID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->modifiedByID=0;
	$row->modified='NULL';
	$duration=intval(getConfig('PonudaDospijece'));
	
	$ponuda_digits=intval(getConfig('ponuda_digits'));
	$next_ponuda=intval(getConfig('next_ponuda'));
	$row->code=sprintf("%0".$ponuda_digits."d",$next_ponuda).$delim.date("Y");
	$now=date("Y-m-d");
	$row->datum_izdavanja=$now;
	$row->datum_dospijeca=$database->getResult("SELECT DATE_ADD(NOW(),INTERVAL $duration DAY)");

	//if(!$row->datum_izdavanja) $row->datum_izdavanja='NULL';
	//if(!$row->datum_dospijeca) $row->datum_dospijeca='NULL';

	$row->check(true);
	$row->store();
	$id=$row->id;
	
	$database->execQuery("INSERT INTO ponuda_stavka (ponudaID,stavkaID,ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust)"
	." SELECT '".$row->id." as ix',stavkaID,ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust"
	." FROM ponuda_stavka WHERE ponudaID=".$rowold->id);
	
	$database->execQuery("UPDATE configuration SET value=GREATEST(value*1,1+$next_ponuda) WHERE type='next_ponuda'");
	
	
	$row=new simPonuda($database);
	$row->load($id);
	
		$row->calculateIznos('',true);
		$row->convertAllDatesToHR();
		$row->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$row->userID));

	
	if($pageopt=='ponuda') {

			$res->addRow('tbl_ponuda',$row,$_SESSION['tbl_ponuda_fields'],'0');			
			$LOG->savelogNOW("Dupliciranje ponuda",$row->naziv." [".$row->code."]",$row->export("PODACI:"));
				
			$res->rowCM("tbl_ponuda",$row->id,$row->klijentID.",".$row->status);
	} 
	$res->alertOK();
	
	$res->closeSimpleDialog();
	
	

?>