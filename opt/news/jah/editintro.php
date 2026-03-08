<?
include_once("opt/news/news.class.php");

	$row=new simNews($database);
	$row->load($id);
	$tmpl->readTemplatesFromInput( "opt/news/jah/editintro.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_INTRO.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('intro');",'jscr');
	$actt->addBlock("initEditor('intro',150);",'jscr');
	
	$res->addAction($act);
	$res->addAction($actt);
	

?>