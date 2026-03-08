<?



include_once("opt/news/news.class.php");


	$rowl=new simNews($database);
	$rowl->bind($_POST);
	$rowl->check(true);
	$rowl->store();
	$rowl->convertVannaLinks();
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_NEWS_INTRO_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>