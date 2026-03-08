<?



include_once("opt/content/content.class.php");


	$rowl=new simContent($database);
	$rowl->bind($_POST);
	$rowl->check(true);
	$rowl->store();
	$rowl->convertVannaLinks();
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_CONT_INTRO_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>