<?
include_once("opt/stavka/folder.class.php");	

$folderID=intval(simGetParam($_REQUEST,'folderID',0));

if ($folderID) {
	$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
	
	$database->setQuery("SELECT stavkaID FROM sta_folder_stavka WHERE stavkaID IN ($ids) AND folderID=$folderID");
	$inArr=$database->loadResultArray();
	
	$database->execQuery("INSERT IGNORE INTO sta_folder_stavka (folderID,stavkaID) (SELECT '$folderID' as fid, id FROM stavka WHERE id IN ($ids) AND id NOT IN (SELECT stavkaID FROM sta_folder_stavka WHERE folderID='$folderID'))");
	$cnt=$database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID ");
	$res->javascript("Ftree.setItemImage($folderID,'folderClosed.gif');changeCMVarByIndex($folderID,Ftree,3,$cnt);loadTable(tblF,'opt=stavka&act=folderlist&folderID=$folderID',$cnt,true);");
	$database->setQuery("SELECT * FROM stavka WHERE id IN (".$ids.")");
	$rows=$database->loadObjectList('id');
	$fld=new simStaFolder($database);
	$fld->load($folderID);
	$msgArr=array();
	foreach($rows as $ix=>$row) {
			if (in_array($ix,$inArr)) $msgArr[]=$row->naziv;
			else $LOG->savelogNOW("Dodavanje stavke u folder",$row->naziv." /// folder: '".$fld->naziv."'",'folderID='.$folderID,$ix);
		}
	$res->alertOK();	
	if(count($inArr)<count($rows)) $database->execQuery("UPDATE sta_folder SET changed=NOW() WHERE id=$folderID");
	if (count($inArr)) $res->alert("Slijedeće stavke već postoje u ovom folderu:\n\n- ".implode("\n- ",$msgArr));
} else $res->alert("Greška pri stavljanju u folder! Pokušajte ponovo ili obavijestite administratora.");
?>