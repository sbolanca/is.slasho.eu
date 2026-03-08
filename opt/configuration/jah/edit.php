<?


$type=trim(simGetParam($_REQUEST,'type',''));
$size=intval(simGetParam($_REQUEST,'size',30));
$numeric=intval(simGetParam($_REQUEST,'numeric',0));
$decimal=intval(simGetParam($_REQUEST,'decimal',0));
$execjs=trim(simGetParam($_REQUEST,'execjs',''));
$condpageopt=trim(simGetParam($_REQUEST,'condpageopt',''));



 $row=new simConfiguration($database);


	$row->load($type);
	
	$tmpl->readTemplatesFromInput( "opt/configuration/jah/edit.html");
	$tmpl->addObject("opt_configuration", $row, "row_",true);
	$tmpl->addVar("opt_configuration", 'size', $size);
	$tmpl->addVar("opt_configuration", 'numeric', $numeric);
	$tmpl->addVar("opt_configuration", 'decimal', $decimal);
	if($numeric) $tmpl->addVar("opt_configuration", 'n','.numeric');
	if($decimal) $tmpl->addVar("opt_configuration", 'd','.decimal');
	$tmpl->addVar("opt_configuration", 'condpageopt', $condpageopt);
	$tmpl->addVar("opt_configuration", 'execjs', $execjs);
	
	
	$cont= $tmpl->getParsedTemplate("opt_configuration");
	
	
	$res->openSimpleDialog("Konfiguracija",$cont,450,3,'green-light');
	if($decimal) $res->javascript("setNumericField('.decimal',true);");
	if($numeric) $res->javascript("setNumericField('.numeric');");

?>