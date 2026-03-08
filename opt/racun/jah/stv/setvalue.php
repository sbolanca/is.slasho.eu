<?
include_once("opt/racun/racun.class.php");
$val=trim(simGetParam($_REQUEST,'val',''));
$fld=trim(simGetParam($_REQUEST,'fld',''));

	
	$row=new simRacunStavka($database);
	$row->load($id);
	
	//$list="ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust,osnovica,pdv,iznos"
	
	$list=array("kolicina","stopa_pdv","cijena","popust");
	
	$alert='';
	$orig=$val;
	switch($fld) {
		case 'cijena': case 'kolicina': 
			$val=makeUSFloat($val,2);
			//if(!$val) $alert="Morate upisati ispravno $fld.";
			$orig=makeHRFloat($val,'',true);
			//$orig=$val;
			break;
		case 'stopa_pdv': case 'popust': 
			$val=intval($val);
			break;
	}
	$row->$fld=$val;
	
	//$alert=$fld;
	if(!$alert) {
		$row->check(true);
		$row->store();
		
		
		if(in_array($fld,$list)) {
			$osnovica=$row->kolicina*$row->cijena*(1-$row->popust/100);
			$pdv=$osnovica*$row->stopa_pdv/100;
			$iznos=$osnovica+$pdv;
			$res->changeCellValueByCode("tbl_stavke",$id,'osnovica',makeHRFloat($osnovica,'',true));
			$res->changeCellValueByCode("tbl_stavke",$id,'pdv',makeHRFloat($pdv,'',true));
			$res->changeCellValueByCode("tbl_stavke",$id,'iznos',makeHRFloat($iznos,'',true));
		}
		$res->changeCellValueByCode("tbl_stavke",$id,$fld,$orig);
		
		if(intval($row->racunID)>0) {
			$rac=new simRacun($database);
			$rac->load($row->racunID);
			$rac->calculateIznos(' kn',true);
	
			$rac->setJavascript($res);
			$rac->modifiedByID=$myID;
			$rac->modified=date("Y-m-d H:i:s");
			$database->execQuery("UPDATE racun SET modifiedByID=$myID,modified=NOW() WHERE id=".$rac->id);
			
			if($pageopt=='racun') {
				$rac->removeSufix();
				$rac->convertAllDatesToHR();
				$rac->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$rac->userID));
				$res->changeRowValues('tbl_racun',$rac,$_SESSION['tbl_racun_fields']);

			}
		}
		
	} else $res->alert($alert);
	
	
	

?>