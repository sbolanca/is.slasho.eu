<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

$galleryID=intval(simGetParam($_REQUEST,'galleryID',0));

	$rowx=new simNews($database);
	$rowx->bind($_POST);
	$rowx->check($isJah);
	$rowx->store();
	require_once( 'lang/hr.php' );
	
	$row=getFullNewsRow($rowx->id,"title,content,menu");

	$GalleryCount=getGalleryItems($galleryID,true);
	if ($GalleryCount) $tmpl->readTemplatesFromInput( "opt/news/show.html");
	else $tmpl->readTemplatesFromInput( "opt/news/simpleshow.html");
	$row->convertVannaLinks();
	
	$tmpl->addObject("opt_news_content", $row, "row_",true); 
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	
	$res->addAction($actt);


?>