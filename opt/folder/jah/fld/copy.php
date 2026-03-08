<?
$parentID=intval(simGetParam($_REQUEST,'parentID',0));

include_once("opt/folder/folder.class.php");	
	
	$row0=new simFolder($database);
	$row0->load($id);
	
	$row=new simFolder($database);
	$row->parentID=0;
	$row->naziv=$row0->naziv;
	$row->userID=$myID;
	$row->sharing=0;
	$row->check(true);
	$row->store();
	
	$database->execQuery("INSERT INTO folder_arhiva (folderID,arhivaID,ordering) (SELECT '".$row->id."' as folderID, arhivaID,ordering FROM folder_arhiva WHERE folderID=$id)");

	$res->addNode('Ftree',$row,$row->naziv);
	$res->rowCM('Ftree',$row->id,"0,$myID,0");

	$database->setQuery("SELECT COUNT(arhivaID) AS cnt FROM folder_arhiva WHERE folderID=".$row->id);
	if (intval($database->loadResult())) $res->javascript("Ftree.setItemImage(".$row->id.",'folderClosed.gif');");

	$LOG->savelog("Kopiranje mape",$row->naziv);

?>
