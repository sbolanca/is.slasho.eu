<?



include_once("opt/news/news.class.php");

	$row=new simGallery($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->store();	
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_NEWS_G_D_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>