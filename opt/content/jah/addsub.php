<?
include_once("opt/content/content.class.php");

	$c=new simContent($database);
	$c->published=1;
	$c->parentID=$id;
	$c->setNextOrdering('parentID='.$id);
	$database->setQuery("SELECT title FROM content_lang WHERE langID=".$lang." AND id=".$id);
	$c->title=_A_CONT_SUB.": ".$database->loadResult();
	$c->store();
	
	
	$act=new jahAction('change','ed_content');
	$act->addBlock("location.href='index.php?opt=content&id=".$c->id."'",'jscr');
	
	$res->addAction($act);

?>