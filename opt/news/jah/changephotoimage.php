<?
include_once("opt/news/news.class.php");
	$file=trim(simGetParam($_REQUEST,'file',''));
	$galleryID=intval(simGetParam($_REQUEST,'gid',0));
	
	$row=new simGalleryItem($database);
	$row->load($id);
	$row->image=$file;
	if (!$id) {
		$row->galleryID=$galleryID;
		$row->published=1;
		$row->setNextOrdering("galleryID=".$row->galleryID);
	}
	$row->store();	
	$row->createCMandTagFields('CM_opt_news_photo');
	$row->addJSfield();
	$tmpl->readTemplatesFromInput( "opt/news/show.html");
		
	$tmpl->addObject("opt_news_gallery_item", $row, "grow_",true);
	$cont= $tmpl->getParsedTemplate("opt_news_gallery_item");
	$tmpl->freeTemplate( "opt_news_gallery_item", true );
		
	
	if ($id) {		
		$act=new jahAction('replace','gi_'.$row->id);
		$act->addBlock($cont);
	} else {
		$act=new jahAction('insertfull','gi_'.$row->id,'gallery');
		$act->addBlock($cont);
		$act2=new jahAction('delete','gi_0','gallery');
		$res->addAction($act2);
	}
	$res->addAction($act);
	
	?>