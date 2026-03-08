<?
include_once("opt/ponuda/ponuda.class.php");
$ponudaID=intval(simGetParam($_REQUEST,'ponudaID',0));

	$rac=new simPonuda($database);
	$rac->load($ponudaID);
	
	$row=new simPonudaStavka($database);
	$row->load($id);
	
	
	
	$tmpl->readTemplatesFromInput( "opt/ponuda/jah/editstavka.html");
	
	$tmpl->addObject("opt_ponuda", $rac, "rac_",true);
	$tmpl->addObject("opt_ponuda", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_ponuda");
	$res->openSimpleDialog("STAVKA PONUDE ".$rac->code,$cont,550,3,'yellow');

	$res->javascript("setNumericField('.numeric');setNumericField('.decimal',true);calcStavka()");
	
	
?>