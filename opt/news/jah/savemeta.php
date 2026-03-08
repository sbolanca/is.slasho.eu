<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

$galleryID=intval(simGetParam($_REQUEST,'galleryID',0));

	$row=new simNews($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->store();
	
	
	$actt=new jahAction('change','editbox');
	
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_NEWS_META_SAVED."');",'jscr');
	
	$res->addAction($actt);


?>