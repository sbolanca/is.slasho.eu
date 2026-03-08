<?

include("opt/racun/racun.class.php");
include("opt/klijent/klijent.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids','')));
$klijentID=intval(simGetParam($_REQUEST,'klijentID',0));

$row=new simRacun($database);

$delim=getConfig('RacunDelimiter');

if($mode=='new') {
	
	$tempID=intval(getConfig('RacunTmpID'));
	$duration=intval(getConfig('RacunDospijece'));
	if($tempID==0) $database->execQuery("INSERT IGNORE INTO configuration (type,value) VALUES ('RacunTmpID',-1)");
	else $database->execQuery("UPDATE configuration SET value='".($tempID-1)."' WHERE type='RacunTmpID'");
	$row->id=$tempID-1;
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->createdByID=$myID;
	$row->code=intval(getConfig('next_racun')).$delim."1".$delim."1";
	$now=date("Y-m-d");
	$row->datum_izdavanja=$now;
	$row->datum_isporuke=$now;
	$row->datum_dospijeca=$database->getResult("SELECT DATE_ADD(NOW(),INTERVAL $duration DAY)");
	$row->vrijeme_izdavanja=date("H:i:s");
	$row->nacin_placanja="transakcijski račun";
	$row->klijentID=$klijentID;
	$row->napomenaID=intval(getConfig('RacunNapomenaID'));
	
	if($klijentID) {
		$t=new simKlijent($database);
		$t->load($klijentID);
		$row->naziv=$t->puni_naziv;
		$row->adresa=$t->adresa;
		$row->sjediste=$t->sjediste;
		$row->oib=$t->oib;
	}
	if($ids) {
		$database->execQuery("SELECT @i:=0");
		$database->execQuery("UPDATE racun_stavka SET ordering=@i:=@i+1 WHERE racunID=-$myID ORDER BY ordering,id DESC");
	}
} else {
	$row->load($id);
	$row->calculateIznos(' €',true);
}
	$c=explode($delim,$row->code);
	foreach($c as $i=>$ci) $row->addField("c".$i,$ci);
	
	
	
	$row->convertAllDatesToHR();
	
	$row->addField('createdby,modifiedby');
	$row->createdby=$database->getResult("SELECT name FROM user WHERE id=".$row->createdByID);
	$row->modifiedby=$database->getResult("SELECT name FROM user WHERE id=".$row->modifiedByID);
	
	$database->setQuery("SELECT id,name,IF(id='".$row->userID."','selected','') as sel FROM user ORDER BY name");
	$oper=$database->loadObjectList();

	$database->setQuery("SELECT id,naziv,IF(id='".$row->napomenaID."','selected','') as sel FROM racun_napomena ORDER BY id");
	$napomene=$database->loadObjectList();

	
	$tmpl->readTemplatesFromInput( "opt/racun/jah/edit.html");
	
	//*********folderi i spremnik
	$sessClipboard=trim(simGetParam($_SESSION,'sta_clipboard',''));
	
	$currFld=intval(simGetParam($_SESSION,'lastOpenFolderStavka',0));
	if($currFld) $flist=$currFld;
	else $flist=0;
	$selected=0;
	$database->setQuery("SELECT f.id,CONCAT('folder: ',f.naziv) as naziv,IF(f.id='$selected','selected','') as sel,COUNT(s.stavkaID) as cnt FROM sta_folder AS f"
		." LEFT JOIN sta_folder_stavka AS s ON s.folderID=f.id"
		." WHERE f.id IN ($flist) GROUP BY f.id ORDER BY FIELD(f.id,$flist)");
		
	$folders=$database->loadObjectList();
		
	$sessClipboard=trim(simGetParam($_SESSION,'sta_clipboard',''));
	if ($sessClipboard) {
			$sobj = new stdClass;
			$sobj->id=-1;
			$sobj->naziv='SPREMIK STAVKI';
			$sobj->cnt=1+substr_count($sessClipboard,',');
			$sobj->sel="selected";
			$folders[]=$sobj;

	}
		
	$tmpl->addObject("opt_f", $folders, "row_",true);
	$tmpl->addVar("opt_f", 'id',$row->id);
//*********folderi i spremnik	
	
	
	$tmpl->addObject("opt_r_o", $oper, "row_",true);
	$tmpl->addObject("opt_r_n", $napomene, "row_",true);
	$tmpl->addObject("opt_racun", $row, "row_",true);
	$tmpl->addVar("opt_racun", "NPSEL".substr($row->nacin_placanja,0,2), "selected");
	$tmpl->addVar("opt_racun", "DELIM", $delim);
	
	$cont= $tmpl->getParsedTemplate("opt_racun");
	

	
	$res->openSimpleDialog((($mode=='new')?"Novi račun":"Račun: ".$row->id),$cont,904,1,'red');

	
	
	$dp="{changeMonth: true,  changeYear: true,yearRange: '-1:+1'}";
	$res->javascript("setNumericField('.numeric');$('#dati').datepicker($dp);$('#dats').datepicker($dp);$('#datd').datepicker($dp);");
	$res->javascript("$('.rfmltv').multipleSelect({minumimCountSelected:2,width:340,position:'top',allSelected:false});",true);

	
		setTblSessions('tbl_stavke');
		
		$res->javascript("initTableHeaderJSVars('tbl_stavke')");
		updateTableHeaderJSVarsJah('tbl_stavke');
		//$res->javascript("defineCMfunc('stavke_cm','tbl_stavke','CM_opt_racunovodstvo_knjiga')");
		
		$cnt=intval($database->getResult("SELECT COUNT(id) FROM racun_stavka WHERE racunID=".$row->id));
		
		$res->javascript('tbl_stavke = new dhtmlXGridObject("gridboxs");
			tbl_stavke.attachEvent("onEditCell",onStavkeEdit);
			tbl_stavke.setImagePath("js/codebase/imgs/");
			Init_tbl_stavke('.$cnt.','.$row->id.');'
			,true);
	


?>