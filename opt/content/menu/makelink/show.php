<?
include_once("opt/content/content.class.php");
if (file_exists('opt/content/lang/'.$simConfig_alang.'.php'))
	require_once( 'opt/content/lang/'.$simConfig_alang.'.php');
	
if (!$row->dbindex) {
	$c=new simContent($database);
	$c->published=1;
	if ($row->id) {
		$database->setQuery("SELECT title FROM menu WHERE id=".$row->id);
		$c->title=$database->loadResult();
	} else if ($row->title) $c->title=$row->title;	
	else $c->title="["._A_CONT_CONT_ADDED." ".date("d.m.Y H.i.s")."][".$lang."]";
	$c->store();

	$row->dbindex=$c->id;
}

$row->link="opt=content&amp;id=".$row->dbindex;


?>