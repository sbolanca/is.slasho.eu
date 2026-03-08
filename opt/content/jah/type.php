<?
include_once("opt/content/content.class.php");

	$row=new simContent($database);
	$row->load($id);
//	$GalleryCount=getGalleryItems($row->galleryID,true);
	$tmpl->readTemplatesFromInput( "opt/content/jah/type.html");
	
	$tmpl->addObject("opt_content_content", $row, "row_",true);
	if ($row->type) $tmpl->addVar("opt_content_content", "S_G", "selected");
	else $tmpl->addVar("opt_content_content", "S_N", "selected");
	simConvertLangConsts($tmpl,"opt_content_content","_A_CONT_T_");
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_TYPE.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>