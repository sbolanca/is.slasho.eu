<?
include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

	$row=getFullContentRow($id,"title,description,keywords");
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/content/jah/editmeta.html");
	
	$tmpl->addObject("opt_content_content", $row, "row_",true);
	simConvertLangConsts($tmpl,"opt_content_content","_A_CONT_M_");
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_META.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>