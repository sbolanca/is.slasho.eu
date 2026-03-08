<?
include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

	$row=getFullContentRow($id,"title,content,menu",false);
	$GalleryCount=getGalleryItems($row->galleryID,true);
	if ($GalleryCount) $tmpl->readTemplatesFromInput( "opt/content/edit.html");
	else $tmpl->readTemplatesFromInput( "opt/content/simpleedit.html");
	
	$tmpl->addObject("opt_content_content", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','contenttext');
	$actt->addBlock($cont);
	$actt->addBlock("initEditor('cont');",'jscr');
	
	$res->addAction($actt);

?>