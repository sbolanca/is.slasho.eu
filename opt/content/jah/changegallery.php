<?
	include_once("opt/content/content.class.php");
	$row=new simContent($database);
	$row->load($id);

	$query="SELECT c.*, IF(c.id=".$row->galleryID.",'selected','') as selected FROM gallery as c "
	."\n ORDER BY c.ordering";
	$database->setQuery($query);
	$galleries=$database->loadObjectList();
	
	$tmpl->readTemplatesFromInput( "opt/content/jah/selectgallery.html");
	
	$tmpl->addObject("opt_content_gallery_option", $galleries, "row_",true);
	$tmpl->addVar("opt_content_gallery", 'contentid',$row->id);
	$cont= $tmpl->getParsedTemplate("opt_content_gallery");
	$tmpl->freeTemplate( "opt_content_gallery", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_SELGAL.": ");

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpgallerylist');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);
	
	?>