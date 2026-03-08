<?

include("opt/kategorija/kategorija.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simKategorija($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/kategorija/jah/edit.html");
	$tmpl->addObject("opt_kategorija", $row, "row_",true);
	//$tmpl->addVar("opt_kategorija", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_kategorija");
	
	
	$res->openSimpleDialog((($mode=='new')?"Nova kategorija":"Kategorija"),$cont,500,1,'pink');


?>