<?
	
	$tmpl->readTemplatesFromInput( "opt/log/jah/optact.html");
	$cont= $tmpl->getParsedTemplate("opt_log");

	$res->change('popuptitle',	'ODABIR AKCIJE');
	$res->change('ed_content',	$cont);
	$res->javascript("showEditPopup('optact');");	
	

?>