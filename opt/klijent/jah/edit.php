<?

include("opt/klijent/klijent.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simKlijent($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} 


	$tmpl->readTemplatesFromInput( "opt/klijent/jah/edit.html");
	$tmpl->addObject("opt_klijent", $row, "row_",true);
	$tmpl->addVar("opt_klijent", 'SRVCHK'.$row->servis, "CHECKED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_klijent");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi klijent":"Klijent"),$cont,500,1,'black');


?>