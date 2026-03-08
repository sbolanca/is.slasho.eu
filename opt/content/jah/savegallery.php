<?



include_once("opt/content/content.class.php");

	$row=new simGallery($database);
	$row->bind($_POST);
	$row->check($isJah);
	if (!$row->store()) echo $row->getError();	
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_CONT_G_D_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>