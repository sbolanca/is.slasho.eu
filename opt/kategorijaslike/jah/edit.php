<?

include("opt/kategorijaslike/kategorijaslike.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simKategorijaSlike($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/kategorijaslike/jah/edit.html");
	$tmpl->addObject("opt_kategorijaslike", $row, "row_",true);
	//$tmpl->addVar("opt_kategorijaslike", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_kategorijaslike");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi kategorija slike":"Kategorija slike"),$cont,500,1,'blue-light');


?>