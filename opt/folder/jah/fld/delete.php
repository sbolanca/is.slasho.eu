<?
include_once("opt/folder/folder.class.php");
	$row=new simFolder($database);
	$row->load($id);
	
	clearTable('folder','id',$id);
	clearTable('folder','parentID',$id);
	clearTable('folder_arhiva','folderID',$id);
	
	$last=isset($_SESSION['lastOpenMap'])?intval(($_SESSION['lastOpenMap'])):0;
	$_SESSION['lastOpenMap']=0;
	$res->deleteNode('Ftree',$id);
	
	$LOG->savelog("Brisanje mape",$row->naziv);
	if ($id==$last) $res->javascript("lastOpenMap=0;");

?>
