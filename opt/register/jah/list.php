<?


	$query="SELECT * FROM user ORDER by username";

	$database->setQuery($query);
	$items=$database->loadObjectList();
	
	$tmpl->readTemplatesFromInput( "opt/register/jah/list.html");
	
	$tmpl->addObject("opt_register_item", $items, "row_",true);
	$tmpl->addVar("opt_register_item", 'LANG', $lang);
	simConvertLangConsts($tmpl,"opt_register","_A_USER_L_");
	$cont= $tmpl->getParsedTemplate("opt_register");
	$tmpl->freeTemplate( "opt_register", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_USER_USERS.": ");

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmplist');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);
	

?>