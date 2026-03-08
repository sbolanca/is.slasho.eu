<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

$galleryID=simGetParam($_REQUEST,'galleryID',0);

	$q="UPDATE news SET galleryID=$galleryID WHERE id=$id";
	$database->setQuery($q);
	$database->query();
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("window.location.reload( true );",'jscr');
	$res->addAction($actt);


?>