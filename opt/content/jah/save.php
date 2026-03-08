<?



include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

$galleryID=simGetParam($_REQUEST,'galleryID',0);

	$row=new simContent($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->store();
	
	$GalleryCount=getGalleryItems($galleryID,true);
	if ($GalleryCount) $tmpl->readTemplatesFromInput( "opt/content/show.html");
	else $tmpl->readTemplatesFromInput( "opt/content/simpleshow.html");
	$row->convertVannaLinks();
	
	$tmpl->addObject("opt_content_content", $row, "row_",true); 
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	
	$res->addAction($actt);


?>