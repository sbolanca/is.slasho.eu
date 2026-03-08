<?
include_once("opt/content/content.class.php");

	$row=new simGallery($database);
	$row->load($id);

	$tmpl->readTemplatesFromInput( "opt/content/jah/editgallery.html");
	$tmpl->addObject("opt_content_gallery", $row, "row_",true);
	$tmpl->addVar("opt_content_gallery", "SUBMITACTION", "activateCMCommandPOST('content','savegallery','contentGalleryForm');");
	$tmpl->addVar("opt_content_gallery", "ACT", "savegallery");
	simConvertLangConsts($tmpl,"opt_content_gallery","_A_CONT_G_");
	
	$cont= $tmpl->getParsedTemplate("opt_content_gallery");
	$tmpl->freeTemplate( "opt_content_gallery", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_GAL.": ".$row->id.($row->title ? ' ['.$row->title.']' : ''));


	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('_tmpgallerydescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>