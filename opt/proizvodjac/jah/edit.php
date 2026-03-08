<?

include("opt/proizvodjac/proizvodjac.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simProizvodjac($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/proizvodjac/jah/edit.html");
	$tmpl->addObject("opt_proizvodjac", $row, "row_",true);
	//$tmpl->addVar("opt_proizvodjac", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_proizvodjac");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi proizvođač":"Proizvodjač"),$cont,500,1,'black');


?>