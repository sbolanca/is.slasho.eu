<?
include_once("opt/ponuda/ponuda.class.php");
$ponudaID=intval(simGetParam($_REQUEST,'ponudaID',$id));

	$rac=new simPonuda($database);
	$rac->load($ponudaID);
	
	$defaultPDV=intval(getConfig('UPDV'))?25:0;
	
	$row=new simPonudaStavka($database);
	$row->mjera='kom';
	$row->popust=0;
	$row->kolicina=1;
	$row->stopa_pdv=$defaultPDV;
	$row->ponudaID=$ponudaID;
	$row->setNextOrdering("ponudaID=".$row->ponudaID);
	
	
	
	$tmpl->readTemplatesFromInput( "opt/ponuda/jah/editstavka.html");
	$tmpl->addObject("opt_ponuda", $rac, "rac_",true);
	
	$tmpl->addObject("opt_ponuda", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_ponuda");
	
	$res->openSimpleDialog("NOVA STAVKA PONUDE ".$rac->code,$cont,550,3,'yellow');
	
	$res->javascript("setNumericField('.numeric');setNumericField('.decimal',true);");
	
	
?>