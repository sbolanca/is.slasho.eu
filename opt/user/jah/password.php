<?
/*
$database->setQuery("SELECT name FROM user WHERE id=$id");
$name=$database->loadResult();
*/
	$row=new simUser($database);
	$row->load($id);

	$tmpl->readTemplatesFromInput( "opt/user/jah/password.html");
	$tmpl->addObject("opt_user", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_user");
	$tmpl->freeTemplate( "opt_user", true );
		
	$res->change('popuptitle','Promjena lozinke');
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	

?>