<?

$ids=$_SESSION['sta_clipboard'];
if($ids) {

	
	$database->setQuery("SELECT *,'' as ix"
	."\n , ".makeSqlCM(true,"CM_opt_stavka_cli","id").", ".makeSqlTagID("cs_","id")
	."\n FROM stavka WHERE id IN ($ids)");
	$rows=$database->loadObjectList();
	for($i=0;$i<count($rows);$i++) {
			$rows[$i]->ix=$i+1;
			$rows[$i]->iznos=makeHRFloat($rows[$i]->iznos);
	}
	$tmpl->readTemplatesFromInput( "opt/stavka/jah/cli/stavke.html");
	$tmpl->addObject( "opt_stavka_s", $rows,'row_',true);
	$cont= $tmpl->getParsedTemplate("opt_stavka");
	
	
	$res->openSimpleDialog("Spremnik stavki",$cont,845,3,'cyan');


} else $res->alert('Spremnik je prazan.',true);
//
?>