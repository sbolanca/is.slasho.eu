<?
include_once("opt/news/news.class.php");
$gid=intval(simGetParam($_REQUEST,'gid',0));
$pid=intval(simGetParam($_REQUEST,'pid',0));

	$c=new simNews($database);
	$c->published=$defPublish;
	$c->listing=0;
	$c->groupId=$gid;	
	$c->parentID=$pid;
	$c->title="Item#";
	//$c->setNextOrdering("parentID=$pid");
	$c->check($isJah);
	$c->store();
	
	
	
	$act=new jahAction('change','ed_content');
	$act->addBlock("window.location.reload( true );",'jscr');
	
	$res->addAction($act);

?>