<?
include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

	$row=new simGroup($database);
	$row->load($id);
	
	$tmpl->readTemplatesFromInput( "opt/news/editgroup.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	$actt->addBlock("initEditor('cont');",'jscr');
	
	$res->addAction($actt);

?>