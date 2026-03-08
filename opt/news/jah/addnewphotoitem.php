<?



include_once("opt/news/news.class.php");
	$cid=intval(simGetParam($_REQUEST,'cid',0));
	$c=new simNews($database);
	$c->load($cid);
	
	$row=new simGalleryItem($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->setNextOrdering("galleryID=".$row->galleryID);
	$row->store();	
	$row->createCMandTagFields('CM_opt_news_photo');
	$row->addJSfield();
	
	$tmpl->readTemplatesFromInput( "opt/news/show.html");
	
	$tmpl->addObject("opt_news_gallery_item", $row, "grow_",true);
	$tmpl->addVar("opt_news_gallery_item",'type', $c->type); 
	$cont= $tmpl->getParsedTemplate("opt_news_gallery_item");
	$tmpl->freeTemplate( "opt_news_gallery_item", true );
	
	$act=new jahAction('insertfull','gi_'.$row->id,'gallery');
	$act->addBlock($cont);
	
	$res->addAction($act);


?>