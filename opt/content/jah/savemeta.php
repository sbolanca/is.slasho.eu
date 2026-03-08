<?



include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

$galleryID=simGetParam($_REQUEST,'galleryID',0);

	$row=new simContent($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->store();
	
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_CONT_META_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>