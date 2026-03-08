<?
include_once("opt/ponuda/ponuda.class.php");
$val=trim(simGetParam($_REQUEST,'val',''));
$fld=trim(simGetParam($_REQUEST,'fld',''));

	
	$row=new simPonudaStavka($database);
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
			$res->changeCellValueByCode("tbl_pstavke",$id,'osnovica',makeHRFloat($osnovica,'',true));
			$res->changeCellValueByCode("tbl_pstavke",$id,'pdv',makeHRFloat($pdv,'',true));
			$res->changeCellValueByCode("tbl_pstavke",$id,'iznos',makeHRFloat($iznos,'',true));
		}
		$res->changeCellValueByCode("tbl_pstavke",$id,$fld,$orig);
		
		if(intval($row->ponudaID)>0) {
			$rac=new simPonuda($database);
			$rac->load($row->ponudaID);
			$rac->calculateIznos(' €',true);
	
			$rac->setJavascript($res);
			$rac->modifiedByID=$myID;
			$rac->modified=date("Y-m-d H:i:s");
			$database->execQuery("UPDATE ponuda SET modifiedByID=$myID,modified=NOW() WHERE id=".$rac->id);
			
			if($pageopt=='ponuda') {
				$rac->removeSufix();
				$rac->convertAllDatesToHR();
				$rac->addField('operater',$database->getResult("SELECT name FROM user WHERE id=".$rac->userID));
				$res->changeRowValues('tbl_ponuda',$rac,$_SESSION['tbl_ponuda_fields']);

			}
		}
		
	} else $res->alert($alert);
	
	
	

?>