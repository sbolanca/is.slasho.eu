<?
include_once("opt/stavka/folder.class.php");
	$row=new simStaFolder($database);
	$row->load($id);
	$row->hide=$row->hide?$row->hide.",$myID":$myID;
	$row->store();


	
	
	$res->deleteNode('Ftree',$id);
	$res->javascript('lastOpenFolder=0');

?>
