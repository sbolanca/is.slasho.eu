<?

include("opt/tipstavke/tipstavke.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simTipStavke($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/tipstavke/jah/edit.html");
	$tmpl->addObject("opt_tipstavke", $row, "row_",true);
	//$tmpl->addVar("opt_tipstavke", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_tipstavke");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi tip stavke":"Tip stavke"),$cont,500,1,'blue-light');


?>