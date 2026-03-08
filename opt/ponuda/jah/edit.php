<?

include("opt/ponuda/ponuda.class.php");
include("opt/klijent/klijent.class.php");

$delim=getConfig('RacunDelimiter');

$mode=trim(simGetParam($_REQUEST,'mode','edit'));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids','')));
$klijentID=intval(simGetParam($_REQUEST,'klijentID',0));

$row=new simPonuda($database);

if($mode=='new') {
	
	$tempID=intval(getConfig('PonudaTmpID'));
	$duration=intval(getConfig('PonudaDospijece'));
	if($tempID==0) $database->execQuery("INSERT IGNORE INTO configuration (type,value) VALUES ('PonudaTmpID',-1)");
	else $database->execQuery("UPDATE configuration SET value='".($tempID-1)."' WHERE type='PonudaTmpID'");
	
	$row->id=$tempID-1;
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->createdByID=$myID;
	$ponuda_digits=intval(getConfig('ponuda_digits'));
	$next_ponuda=intval(getConfig('next_ponuda'));
	$row->code=sprintf("%0".$ponuda_digits."d",$next_ponuda).$delim.date("Y");
	$now=date("Y-m-d");
	$row->datum_izdavanja=$now;
	$row->datum_dospijeca=$database->getResult("SELECT DATE_ADD(NOW(),INTERVAL $duration DAY)");
	$row->nacin_placanja="transakcijski ponuda";
	$row->klijentID=$klijentID;
	
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
		$database->execQuery("UPDATE ponuda_stavka SET ordering=@i:=@i+1 WHERE ponudaID=-$myID ORDER BY ordering,id DESC");
	}
} else {
	$row->load($id);
	$row->calculateIznos(' €',true);
}
	$c=explode("/",$row->code);
	foreach($c as $i=>$ci) $row->addField("c".$i,$ci);
	
	
	
	$row->convertAllDatesToHR();
	
	$row->addField('createdby,modifiedby');
	$row->createdby=$database->getResult("SELECT name FROM user WHERE id=".$row->createdByID);
	$row->modifiedby=$database->getResult("SELECT name FROM user WHERE id=".$row->modifiedByID);
	
	$database->setQuery("SELECT id,name,IF(id='".$row->userID."','selected','') as sel FROM user ORDER BY name");
	$oper=$database->loadObjectList();
	$database->setQuery("SELECT id,naziv,IF(id='".$row->napomenaID."','selected','') as sel FROM racun_napomena ORDER BY id");
	$napomene=$database->loadObjectList();
	
	$tmpl->readTemplatesFromInput( "opt/ponuda/jah/edit.html");
	
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
	$tmpl->addObject("opt_ponuda", $row, "row_",true);
	$tmpl->addObject("opt_r_n", $napomene, "row_",true);
	$tmpl->addVar("opt_ponuda", "NPSEL".substr($row->nacin_placanja,0,2), "selected");
	$tmpl->addVar("opt_ponuda", "DELIM", $delim);
	
	$cont= $tmpl->getParsedTemplate("opt_ponuda");
	

	
	$res->openSimpleDialog((($mode=='new')?"Nova ponuda":"Ponuda: ".$row->id),$cont,904,1,'blue');

	
	
	$dp="{changeMonth: true,  changeYear: true,yearRange: '-1:+1'}";
	$res->javascript("setNumericField('.numeric');$('#dati').datepicker($dp);$('#dats').datepicker($dp);$('#datd').datepicker($dp);");
	$res->javascript("$('.rfmltv').multipleSelect({minumimCountSelected:1,width:340,position:'top',allSelected:false});",true);

	
		setTblSessions('tbl_pstavke');
		
		$res->javascript("initTableHeaderJSVars('tbl_pstavke')");
		updateTableHeaderJSVarsJah('tbl_pstavke');
		//$res->javascript("defineCMfunc('pstavke_cm','tbl_pstavke','CM_opt_ponudaovodstvo_knjiga')");
		
		$cnt=intval($database->getResult("SELECT COUNT(id) FROM ponuda_stavka WHERE ponudaID=".$row->id));
		
		$res->javascript('tbl_pstavke = new dhtmlXGridObject("gridboxp");
			tbl_pstavke.attachEvent("onEditCell",onPStavkeEdit);
			tbl_pstavke.setImagePath("js/codebase/imgs/");
			Init_tbl_pstavke('.$cnt.','.$row->id.');'
			,true);
	


?>