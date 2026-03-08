<?
include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");
$tf=trim(simGetParam($_REQUEST,'tf',''));

	$file=trim(simGetParam($_REQUEST,'file',''));
	
	include_once('lang/'.strtolower($lang).'.php');
	
	$row=new simNews($database);
	$row->load($id);
	$row->image=$file;
	
	$row->store();	
	
	loadNewsTemplate($tmpl,$tf);
	
	$row=getFullNewsRow($id,$lang,"title,intro,menu");
	$row->addCMField("CM_opt_news_intro",$row->id,"'".$row->parentID."'","'$tf'");
	$row->addTAGIDField("n_");
	
	if ($row->menu) $row->title=$row->menu;

	$tmpl->addObject("opt_news_contentlist", $row, "row_",true); 
	$tmpl->addVar("opt_news_contentlist",'_MORE', _MORE); 
	$tmpl->addVar("opt_news_contentlist",'LANG', $lang); 
	$cont= $tmpl->getParsedTemplate("opt_news_contentlist");
	$tmpl->freeTemplate( "opt_news_contentlist", true );
	
	$actt=new jahAction('replace','n_'.$row->id);
	$actt->addBlock($cont);
	
	$res->addAction($actt);
	
	?>