<?

include("opt/kategorijastavke/kategorijastavke.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simKategorijaStavke($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/kategorijastavke/jah/edit.html");
	$tmpl->addObject("opt_kategorijastavke", $row, "row_",true);
	//$tmpl->addVar("opt_kategorijastavke", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_kategorijastavke");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi kategorija stavke":"Kategorija stavke"),$cont,500,1,'blue-light');


?>