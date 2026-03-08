<?
include_once("opt/racun/racun.class.php");
$racunID=intval(simGetParam($_REQUEST,'racunID',$id));

	$rac=new simRacun($database);
	$rac->load($racunID);
	
	$defaultPDV=intval(getConfig('UPDV'))?25:0;
	
	$row=new simRacunStavka($database);
	$row->mjera='kom';
	$row->popust=0;
	$row->kolicina=1;
	$row->stopa_pdv=$defaultPDV;
	$row->racunID=$racunID;
	$row->setNextOrdering("racunID=".$row->racunID);
	
	
	
	$tmpl->readTemplatesFromInput( "opt/racun/jah/editstavka.html");
	$tmpl->addObject("opt_racun", $rac, "rac_",true);
	
	$tmpl->addObject("opt_racun", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_racun");
	
	$res->openSimpleDialog("NOVA STAVKA RAČUNA ".$rac->code,$cont,550,3,'green');
	
	$res->javascript("setNumericField('.numeric');setNumericField('.decimal',true);");
	
	
?>