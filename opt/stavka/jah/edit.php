<?

include("opt/stavka/stavka.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simStavka($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} else {
	$row->stopa_pdv=getConfig('PDV');
}
$database->setQuery("SELECT k.*,IF(k.id=".intval($row->kategorijaID).",'selected','') as sel FROM kategorijastavke AS k");
$kats=$database->loadObjectList();
$database->setQuery("SELECT t.*,IF(t.id=".intval($row->tipID).",'selected','') as sel FROM tipstavke AS t");
$tips=$database->loadObjectList();

	$tmpl->readTemplatesFromInput( "opt/stavka/jah/edit.html");
	$tmpl->addVar("opt_stavka", 'CHKM'.$row->materijal, "checked");
	$tmpl->addObject("opt_stavka", $row, "row_",true);
	$tmpl->addObject("opt_k", $kats, "row_",true);
	$tmpl->addObject("opt_t", $tips, "row_",true);
	//$tmpl->addVar("opt_stavka", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_stavka");
	
	
	$res->openSimpleDialog((($mode=='new')?"Nova stavka":"Stavka"),$cont,570,1,'green');
	$res->javascript("setNumericField('.decimal',1)");

?>