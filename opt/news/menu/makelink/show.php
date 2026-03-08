<?
include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");
$index_item=intval(simGetParam($_REQUEST,'index_item',0));

if (!$row->dbindex && !($row->mact=='alist')) {
	$c=new simGroup($database);
	$c->published=1;
	$database->setQuery("SELECT title FROM menu WHERE id=".$row->id);
	$c->title=$database->loadResult();
	$c->store();
	$row->dbindex=$c->id;
}
if ($row->mact && !($row->mact=='show')) {
	$row->link="opt=news&amp;act=".$row->mact."&amp;id=".$row->dbindex;
} else {
	$grindex=$row->dbindex;
	$row->dbindex=$index_item;
	if (!$row->dbindex) {
		$n=new simNews($database);
		$n->published=1;
		$n->groupID=$grindex;
		$database->setQuery("SELECT title FROM menu WHERE id=".$row->id);
		$n->title=$database->loadResult();
		$n->store();
		$row->dbindex=$n->id;
	}
	$database->setQuery("SELECT '' as link, c.id,c.parentID, COUNT(DISTINCT(n.id)) as subcnt"
	."\n FROM news as c "
	."\n LEFT JOIN news as n ON n.parentID=c.id "
	."\n WHERE c.id=".$row->dbindex
	."\n GROUP BY c.id");
	$database->loadObject($tr);
	 processNewsLink($tr);
	$row->link="opt=news&amp;".$tr->link;
}

?>