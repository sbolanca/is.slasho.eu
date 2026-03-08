<?
include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

	$row=getFullNewsRow($id,"title,content,menu",false);
	$GalleryCount=getGalleryItems($row->galleryID,true);
	if ($GalleryCount) $tmpl->readTemplatesFromInput( "opt/news/edit.html");
	else $tmpl->readTemplatesFromInput( "opt/news/simpleedit.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	$actt->addBlock("initEditor('cont');",'jscr');
	
	$res->addAction($actt);

?>