<?
include_once("opt/content/content.class.php");
	$file=trim(simGetParam($_REQUEST,'file',''));
	$galleryID=intval(simGetParam($_REQUEST,'gid',0));
	
	$cid=intval(simGetParam($_REQUEST,'cid',0));
	$c=new simContent($database);
	$c->load($cid);
	
	$row=new simGalleryItem($database);
	$row->load($id);
	$row->image=$file;
	if (!$id) {
		$row->galleryID=$galleryID;
		$row->published=1;
		$row->setNextOrdering("galleryID=".$row->galleryID);
	}
	$row->store();	
	$row->createCMandTagFields();
	$row->addJSfield();
	$tmpl->readTemplatesFromInput( "opt/content/show.html");
		
	$tmpl->addObject("opt_content_gallery_item", $row, "grow_",true);
	$tmpl->addVar("opt_content_gallery_item",'type', $c->type); 
	$cont= $tmpl->getParsedTemplate("opt_content_gallery_item");
	$tmpl->freeTemplate( "opt_content_gallery_item", true );
		
	
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