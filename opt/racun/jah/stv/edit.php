<?
include_once("opt/racun/racun.class.php");
$racunID=intval(simGetParam($_REQUEST,'racunID',0));

	$rac=new simRacun($database);
	$rac->load($racunID);
	
	$row=new simRacunStavka($database);
	$row->load($id);
	
	
	
	$tmpl->readTemplatesFromInput( "opt/racun/jah/editstavka.html");
	
	$tmpl->addObject("opt_racun", $rac, "rac_",true);
	$tmpl->addObject("opt_racun", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_racun");
	$res->openSimpleDialog("STAVKA RAČUNA ".$rac->code,$cont,550,3,'green');

	$res->javascript("setNumericField('.numeric');setNumericField('.decimal',true);calcStavka()");
	
	
?>