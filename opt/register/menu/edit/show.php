<?

	if (!$row->dbindex) $row->dbindex=0;
	
	$tmpl->readTemplatesFromInput( "opt/register/menu/".$row->type.".html");
	
	switch ($row->mact) {
			case 'logout':	$var='SELL'; break;
			default: $var='SELS';
	}

		
	$tmpl->addVar("opt_register_menu",$var,'checked');
	$menuspecific= $tmpl->getParsedTemplate("opt_register_menu");
	$tmpl->freeTemplate( "opt_register_menu", true );
	

?>