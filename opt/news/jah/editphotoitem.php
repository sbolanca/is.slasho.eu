<?
include_once("opt/news/news.class.php");

	$row=new simGalleryItem($database);
	$row->load($id);
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/news/jah/editphotoitem.html");
	$tmpl->addObject("opt_news_photo", $row, "row_",true);
	simConvertLangConsts($tmpl,"opt_news_photo","_A_NEWS_G_");
	$cont= $tmpl->getParsedTemplate("opt_news_photo");
	$tmpl->freeTemplate( "opt_news_photo", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_PHOTO.": ".$row->id.($row->title ? ' ['.$row->title.']' : ''));

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpimgdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>