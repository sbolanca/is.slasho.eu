<?
include_once("opt/news/news.class.php");
$tf=trim(simGetParam($_REQUEST,'tf',''));

	$row=new simNews($database);
	$row->load($id);
	$row->convertSQLDateToHR('start_date');
	$row->convertSQLDateToHR('end_date');
	$tmpl->readTemplatesFromInput( "opt/news/jah/editdate.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	simProcessRadio($tmpl,"opt_news_content", "CKF", $row->frontpage);
	simProcessRadio($tmpl,"opt_news_content", "CKP", $row->published);
	simProcessRadio($tmpl,"opt_news_content", "CKL", $row->listing);
	simProcessRadio($tmpl,"opt_news_content", "CKA", $row->archive);
	$tmpl->addVar("opt_news_content",'tf', $tf); 
	simConvertLangConsts($tmpl,"opt_news_content","_A_NEWS_P_");
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_PUBLISHING.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>