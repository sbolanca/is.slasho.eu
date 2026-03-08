<?
include_once("opt/news/news.class.php");
$tf=trim(simGetParam($_REQUEST,'tf',''));

	$row=new simNews($database);
	$row->load($id);
	
	if ($tf) $tmpl->readTemplatesFromInput( "opt/news/edit".$tf.".html");
	else $tmpl->readTemplatesFromInput( "opt/news/editlist.html");
	 
	$tmpl->addObject("opt_news_contentlist", $row, "row_",true);
	$tmpl->addVar("opt_news_contentlist",'tf', $tf); 
	$cont= $tmpl->getParsedTemplate("opt_news_contentlist");
	$tmpl->freeTemplate( "opt_news_contentlist", true );
		
	$act=new jahAction('change','n_'.$row->id);
	$act->addBlock($cont);
	$act->addBlock("initEditor('intro',160);",'jscr');
	
	$res->addAction($act);
	

?>