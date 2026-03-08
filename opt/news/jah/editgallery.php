<?
include_once("opt/news/news.class.php");

	$row=new simGallery($database);
	$row->load($id);

	$tmpl->readTemplatesFromInput( "opt/news/jah/editgallery.html");
	$tmpl->addObject("opt_news_gallery", $row, "row_",true);
	$tmpl->addVar("opt_news_gallery", "SUBMITACTION", "activateCMCommandPOST('news','savegallery','newsGalleryForm');");
	$tmpl->addVar("opt_news_gallery", "ACT", "savegallery");

	simConvertLangConsts($tmpl,"opt_news_gallery","_A_NEWS_G_");
	$cont= $tmpl->getParsedTemplate("opt_news_gallery");
	$tmpl->freeTemplate( "opt_news_gallery", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_GAL.": ".$row->id.($row->title ? ' ['.$row->title.']' : ''));


	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpgallerydescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>