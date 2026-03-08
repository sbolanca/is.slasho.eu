<?



include_once("opt/content/content.class.php");

	$cid=intval(simGetParam($_REQUEST,'cid',0));
	$c=new simContent($database);
	$c->load($cid);
	$row=new simGalleryItem($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->setNextOrdering("galleryID=".$row->galleryID);
	$row->store();	
	$row->createCMandTagFields();
	$row->addJSfield();	
	$tmpl->readTemplatesFromInput( "opt/content/show.html");
	
	$tmpl->addObject("opt_content_gallery_item", $row, "grow_",true);
	$tmpl->addVar("opt_content_gallery_item",'type', $c->type); 
	$cont= $tmpl->getParsedTemplate("opt_content_gallery_item");
	$tmpl->freeTemplate( "opt_content_gallery_item", true );
	
	$act=new jahAction('insert','gi_'.$row->id,'gallery');
	$act->addBlock($cont);
	
	$res->addAction($act);


?>