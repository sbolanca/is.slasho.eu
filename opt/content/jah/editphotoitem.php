<?
include_once("opt/content/content.class.php");

	$row=new simGalleryItem($database);
	$row->loadLang($id,"title,description");
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/content/jah/editphotoitem.html");
	$tmpl->addObject("opt_content_photo", $row, "row_",true);
	simConvertLangConsts($tmpl,"opt_content_photo","_A_CONT_G_");
	$cont= $tmpl->getParsedTemplate("opt_content_photo");
	$tmpl->freeTemplate( "opt_content_photo", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_PHOTO.": ".$row->id.($row->title ? ' ['.$row->title.']' : ''));

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpimgdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>