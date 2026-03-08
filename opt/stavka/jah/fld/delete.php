<?
include_once("opt/stavka/folder.class.php");
	$row=new simStaFolder($database);
	$row->load($id);
	$subs=loadSubIds('sta_folder',$id);
	$list=implode(",",$subs);
	clearTable('sta_folder','id',$list);
	clearTable('sta_folder_stavka','folderID',$list);


	//
	$res->sound('beep');
	$res->deleteNode('Ftree',$id);
	
	$LOG->savelog("Brisanje foldera stavki",$row->naziv);

?>
