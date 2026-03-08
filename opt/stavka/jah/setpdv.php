<?

include("opt/stavka/stavka.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

	$tmpl->readTemplatesFromInput( "opt/stavka/jah/setpdv.html");
	$tmpl->addVar("opt_stavka", 'ids',$ids);
	$tmpl->addVar("opt_stavka", 'pdv',getConfig('PDV'));
	
	//$tmpl->addVar("opt_stavka", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_stavka");
	
	
	$res->openSimpleDialog("PDV",$cont,250,2,'blue');
	$res->javascript("setNumericField('.decimal',1)");

?>