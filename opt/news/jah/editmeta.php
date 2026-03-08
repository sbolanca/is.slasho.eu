<?
include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

	$row=getFullNewsRow($id,"title,description,keywords");
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/news/jah/editmeta.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	simConvertLangConsts($tmpl,"opt_news_content","_A_NEWS_M_");
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_META.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>