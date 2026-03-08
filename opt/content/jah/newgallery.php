<?
include_once("opt/content/content.class.php");

	$row=new simGallery($database);
	$crow=new simContent($database);
	$crow->load($id);
	$row->title=$crow->title;
	$tmpl->readTemplatesFromInput( "opt/content/jah/editgallery.html");
	$tmpl->addObject("opt_content_gallery", $crow, "row_",true);
	$tmpl->addVar("opt_content_gallery", "SUBMITACTION", "activateCMCommandPOST('content','addgallery','contentGalleryForm');");
	$tmpl->addVar("opt_content_gallery", "CONTENTID", $id);
	$tmpl->addVar("opt_content_gallery", "ACT", "addgallery");
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