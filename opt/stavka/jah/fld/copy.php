<?
$parentID=intval(simGetParam($_REQUEST,'parentID',0));

include_once("opt/stavka/folder.class.php");	
	
	$row0=new simStaFolder($database);
	$row0->load($id);
	
	$row=new simStaFolder($database);
	$row->parentID=0;
	$row->naziv=$row0->naziv;
	$row->userID=$myID;
	$row->sharing=0;
	$row->created=date("Y-m-d H:i:s");
	$row->check(true);
	$row->store();
	
	$database->execQuery("INSERT INTO sta_folder_stavka (folderID,stavkaID,ordering) (SELECT '".$row->id."' as folderID, stavkaID,ordering FROM sta_folder_stavka WHERE folderID=$id)");

	$res->addNode('Ftree',$row,$row->naziv);
	$res->nodeCM('Ftree',$row->id,"0,$myID,0");

	$database->setQuery("SELECT COUNT(stavkaID) AS cnt FROM sta_folder_stavka WHERE folderID=".$row->id);
	if (intval($database->loadResult())) $res->javascript("Ftree.setItemImage(".$row->id.",'folderClosed.gif');");

	$LOG->savelog("Kopiranje foldera stavki",$row->naziv);

?>
