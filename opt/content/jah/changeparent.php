<?
include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

	$row=getFullContentRow($id,"title");
	
	$q="SELECT c.id,c.title, IF(c.id=".$row->parentID.",'selected','') as sel FROM content AS c WHERE c.id<>$id ORDER BY c.title,c.parentID, c.id";
	$database->setQuery($q);
	$pars=$database->loadObjectList();
	
	$tmpl->readTemplatesFromInput( "opt/content/jah/changeparent.html");
	
	$tmpl->addObject("opt_content_content", $row, "row_",true);
	$tmpl->addObject("opt_content_parent", $pars, "par_",true);
	$tmpl->addVAR("opt_content_parent", "_A_CONT_P_NO", _A_CONT_P_NO);
	
	$cont= $tmpl->getParsedTemplate("opt_content_content");
	$tmpl->freeTemplate( "opt_content_content", true );
	
	$actt=new jahAction('change','popuptitle');
	$actt->addBlock(_A_CONT_PARENT.": ".$row->title);

	$act=new jahAction('change','ed_content');
	$act->addBlock($cont);
	$act->addBlock("showEditPopup('popupdescription');",'jscr');
	
	$res->addAction($actt);
	$res->addAction($act);

?>