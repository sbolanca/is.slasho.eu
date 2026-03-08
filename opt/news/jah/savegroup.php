<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");



	$row=new simGroup($database);
	$row->bind($_POST);
	$row->check(true);
	$row->store();
	$row->convertVannaLinks();
	
    $tmpl->readTemplatesFromInput( "opt/news/blog.html");

	$tmpl->addObject("opt_news_content", $row, "row_",true); 
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	
	$res->addAction($actt);


?>