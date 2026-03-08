<?
include_once("opt/news/news.class.php");
$class=trim(simGetParam($_REQUEST,'class','News'));
	
	$cln='sim'.$class;
	$row=new $cln($database);
	$row->load($id);
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/news/jah/type.html");
	
	$tmpl->addObject("opt_news_content", $row, "row_",true);
	if ($row->type) $tmpl->addVar("opt_news_content", "S_G", "selected");
	else $tmpl->addVar("opt_news_content", "S_N", "selected");
	 $tmpl->addVar("opt_news_content", "class", $class);
	simConvertLangConsts($tmpl,"opt_news_content","_A_NEWS_T_");
	$cont= $tmpl->getParsedTemplate("opt_news_content");
	$tmpl->freeTemplate( "opt_news_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_NEWS_TYPE.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>