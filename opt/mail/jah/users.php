<?



$ids=trim(simGetParam($_REQUEST,'ids',''));


$database->setQuery("SELECT * FROM user WHERE id IN (".str_replace("|",",",$ids).")");
$rows=$database->loadObjectList();

	$tmpl->readTemplatesFromInput( "opt/mail/jah/users.html");

	$tmpl->addObject("opt_mail_u", $rows, "row_",true);
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	
	$res->openSimpleDialog("Popis za slanje maila",$cont,500,5,'blue-full');

?>