<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");


require_once( 'lang/'.strtolower($lang).'.php' );

$dln=simGetParam($_POST,'start_date','');
$tf=trim(simGetParam($_REQUEST,'tf',''));
	$rowl=new simNews($database);
	$rowl->load($id);
	$d1o=$rowl->start_date;
	$rowl->convertSQLDateToHR('start_date');
	
	$rowl->convertSQLDateToHR('end_date');
	$rowl->bind($_POST);
	$rowl->check(true);
	if(substr($d1o,0,10)==$rowl->start_date) $rowl->start_date=$d1o;
	$rowl->store();
	
	loadNewsTemplate($tmpl,$tf);
	
	$row=getFullNewsRow($rowl->id,"title,intro,menu");
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
		
	$actt->addBlock("hideStandardPopup('editbox');",'jscr');
	if (!$hideMSG) $actt->addBlock("alert('"._A_NEWS_PUBL_SAVED."');",'jscr');
	
	
	$res->addAction($actt);


?>