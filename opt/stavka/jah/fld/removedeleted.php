<?
include_once("opt/stavka/folder.class.php");	

$folderID=intval(simGetParam($_REQUEST,'folderID',$id));

$fld=new simStaFolder($database);
	$fld->load($folderID);

$database->setQuery("SELECT s.* FROM sta_folder_stavka AS f LEFT JOIN stavka as s ON s.id=f.stavkaID WHERE s.recyclebin=1 AND f.folderID=$folderID");
$rows=$database->loadObjectList('id');

if(count($rows)) {
	$database->execQuery("DELETE FROM sta_folder_stavka WHERE stavkaID IN (".implode(",",array_keys($rows)).") AND folderID=$folderID");
	$cnt=$database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID ");
	$res->javascript("changeCMVarByIndex($folderID,Ftree,3,$cnt);");

	foreach($rows as $ix=>$row) {
		$LOG->savelogNOW("Uklanjaje izbrisanih stavki iz foldera",$row->naziv." / ".$row->izvodjac." /// folder '".$fld->naziv."'",'',$ix);
		$res->deleteRow("tblF",$ix);
	}
	$res->sound('rattle');
	 $database->execQuery("UPDATE sta_folder SET changed=NOW() WHERE id=$folderID");
	
} else $res->alert("Nema izbrisanih snimaka u folderu.");
?>