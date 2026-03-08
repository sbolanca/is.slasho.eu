<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");
	$tf=trim(simGetParam($_REQUEST,'tf',''));
	
	$rowx=new simNews($database);
	$rowx->bind($_POST);
	$rowx->check($isJah);
	$rowx->store();
	
	require_once( 'lang/hr.php' );
	
	loadNewsTemplate($tmpl,$tf);
	
	$row=getFullNewsRow($rowx->id,$rowx->langID,"title,menu,intro");
	$row->addCMField("CM_opt_news_intro",$row->id,"'".$row->parentID."'","'$tf'");
	$row->addTAGIDField("n_");	
	//if ($row->menu) $row->title=$row->menu;
	
	if (!$row->image) $row->image='blank.jpg';
	else  setThumbScript($row);
	

	$tmpl->addObject("opt_news_contentlist", $row, "row_",true); 
	$tmpl->addVar("opt_news_contentlist",'_MORE', _MORE); 
	$cont= $tmpl->getParsedTemplate("opt_news_contentlist");
	$tmpl->freeTemplate( "opt_news_contentlist", true );
	
	$actt=new jahAction('replace','n_'.$row->id);
	$actt->addBlock($cont);
	
	$res->addAction($actt);


?>