<?
	$row=new simUser($database);
	$row->load($id);


	
	$tmpl->readTemplatesFromInput( "opt/user/jah/edit.html");
	
		
	
	$tmpl->addObject("opt_user", $row, "row_",true);
	$tmpl->addVar("opt_user", 'SEL'.$row->colorset, 'SELECTED');
	$tmpl->addVar("opt_user", "SELF".$row->mailfreq, "SELECTED");
	$tmpl->addVar("opt_user", 'ACTSEL'.$row->active, 'checked');
	$tmpl->addVar("opt_user", 'ADMSEL'.$row->admin, 'checked');
	$tmpl->addVar( "opt_user_spec", "SUPER", $isSuper);
	$tmpl->addVar("opt_user_spec", 'SUPSEL'.$row->super, 'checked');
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_user");

	$res->change('popuptitle',	$row->id.": ".$row->name);
	$res->change('ed_content',	$cont);
	$res->javascript("showEditPopup('popupdescription');");	
	

?>