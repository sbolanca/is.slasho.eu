<?
	$row=new simUser($database);


	
	$tmpl->readTemplatesFromInput( "opt/user/jah/edit.html");
	
		
	
	$tmpl->addObject("opt_user", $row, "row_",true);
	$tmpl->addVar( "opt_user_spec", "SUPER", $isSuper);
	$tmpl->addVar("opt_user_spec", 'SUPSEL0', 'checked');
	$tmpl->addVar("opt_user", 'GLUSEL0', 'checked');
	$tmpl->addVar("opt_user", 'ADMSEL0', 'checked');
	$tmpl->addVar("opt_user", 'SELSTRONG', 'SELECTED');
	$tmpl->addVar("opt_user", "SELFDAILY", "SELECTED");
	$tmpl->addVar("opt_user", "ROW_AKTUALNO", "15");

	$cont= $tmpl->getParsedTemplate("opt_user");

	$res->change('popuptitle',	'Dodavanje novog djelatnika');
	$res->change('ed_content',	$cont);
	$res->javascript("showEditPopup('popupdescription');");	
	

?>