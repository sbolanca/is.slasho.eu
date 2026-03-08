<?



$qopt=trim(simGetParam($_REQUEST,'qopt','snimka'));

$database->setQuery("SELECT * FROM specijalniupit WHERE qopt='$qopt' ORDER BY ordering" );
$rows=$database->loadObjectList();

	$tmpl->readTemplatesFromInput( "opt/specijalniupit/jah/show.html");
	$tmpl->addObject("opt_s_q", $rows, "row_",true);
	$tmpl->addVar("opt_specijalniupit", 'qopt', $qopt);
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_specijalniupit");
	
	
	$res->openSimpleDialog(strtoupper($qopt).": Specijalno pretraživanje",$cont,600,2,'yellow-light');


?>