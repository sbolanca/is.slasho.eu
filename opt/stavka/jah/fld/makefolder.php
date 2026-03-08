<?
$parentID=intval(simGetParam($_REQUEST,'parentID',0));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));


include_once("opt/stavka/folder.class.php");	
	
		
	
	
	$row=new simStaFolder($database);
	$row->parentID=$parentID;
	$row->naziv=($parentID ? '- Novi podfolder / ' : ' - Novi folder / ');
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->check(true);
	$row->store();
	
	if (intval($row->id)) {
		$database->execQuery("INSERT IGNORE INTO sta_folder_stavka (folderID,stavkaID) (SELECT '".$row->id."' as folderID, id FROM stavka WHERE id IN (".$ids."))");

		$database->setQuery("SELECT * FROM stavka WHERE id IN (".$ids.")");
		$rows=$database->loadObjectList('id');
		foreach($rows as $ix=>$r) {
				$LOG->savelogNOW("Dodavanje stavke u folder grupiranjem",$r->naziv." /// folderID=".$row->id,'folderID='.$row->id,$ix);
			}
		$cnt=$database->getResult("SELECT COUNT(stavkaID) FROM folder_stavka WHERE folderID=".$row->id);
		$LOG->savelogNOW("Kreiranje foldera grupiranjem","[broj stavki: ".count(explode(",",$ids))."]",$ids,$row->id);
	
		
		$res->addNode('Ftree',$row,addslashes($row->naziv));
		$res->javascript("Ftree.selectItem(".$row->id.");");
		$res->nodeCM('Ftree',$row->id,$row->parentID.",$myID,0,$cnt");
		$res->javascript("refreshFolder(".$row->id.");");
		$res->javascript("Ftree.selectItem(".$row->id.");");
		$res->javascript("currentItem=".$row->id.";");
		$res->javascript("activateCMCommand('stavka','fld-edit');");
		$res->javascript("javascript:showEPopup('folderbox','foldercontent','folderboxdrag','tabsf','foldertable');");
		$res->javascript("Ftree.setItemImage(".$row->id.",'folderClosed.gif');");
	} else  $res->alert("Greška pri kreiranju foldera! Pokušajte ponovo ili obavijestite administratora.");
	
?>
