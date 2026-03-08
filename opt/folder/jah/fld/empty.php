<?
include_once("opt/folder/folder.class.php");
$folderID=intval(simGetParam($_REQUEST,'folderID',$id));


	$row=new simFolder($database);
	$row->load($folderID);
	
	clearTable('folder_arhiva','folderID',$folderID);
	
	$cnt=$database->getResult("SELECT COUNT(id) FROM folder WHERE parentID=$folderID");
	$imgscr=$cnt?'':"Ftree.setItemImage($folderID,'leaf.gif');";
	$res->javascript("$imgscr changeCMVarByIndex($folderID,Ftree,3,0);tblF.clearAll();");

	$LOG->savelog("Pražnjenje mape",$row->naziv);

?>
