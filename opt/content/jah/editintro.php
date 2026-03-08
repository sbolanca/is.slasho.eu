<?
include_once("opt/content/content.class.php");

	$row=new simContent($database);
	$row->load($id);
	$tmpl->readTemplatesFromInput( "opt/content/jah/editintro.html");
	
	$tmpl->addObject("opt_content_content", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_INTRO.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('intro');",'jscr');
	$actt->addBlock("initEditor('intro',150);",'jscr');
	
	$res->addAction($act);
	$res->addAction($actt);
	

?>