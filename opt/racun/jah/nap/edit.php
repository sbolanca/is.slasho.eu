<?

include("opt/racun/racun.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simRacunNapomena($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/racun/jah/editnapomena.html");
	$tmpl->addObject("opt_racun", $row, "row_",true);
	//$tmpl->addVar("opt_proizvodjac", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_racun");
	
	
	$res->openSimpleDialog((($mode=='new')?"Nova napomena":"Napomena"),$cont,700,1,'green');


?>