<?



include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

$galleryID=simGetParam($_REQUEST,'galleryID',0);

	$q="UPDATE content SET galleryID=$galleryID WHERE id=$id";
	$database->setQuery($q);
	$database->query();
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("location.href='index.php?opt=content&id=$id';",'jscr');
	$res->addAction($actt);


?>