<?
	include_once("opt/news/news.class.php");
	$row=new simNews($database);
	$row->load($id);

	$query="SELECT c.*, IF(c.id=".$row->galleryID.",'selected','') as selected FROM gallery as c "
	."\n ORDER BY c.ordering";
	$database->setQuery($query);
	$galleries=$database->loadObjectList();
	
	$tmpl->readTemplatesFromInput( "opt/news/jah/selectgallery.html");
	
	$tmpl->addObject("opt_news_gallery_option", $galleries, "row_",true);
	$tmpl->addVar("opt_news_gallery", 'contentid',$row->id);
	$cont= $tmpl->getParsedTemplate("opt_news_gallery");
	$tmpl->freeTemplate( "opt_news_gallery", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_SELGAL);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpgallerylist');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);
	
	?>