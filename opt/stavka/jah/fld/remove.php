<?
include_once("opt/stavka/folder.class.php");	

$folderID=intval(simGetParam($_REQUEST,'folderID',0));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$fld=new simStaFolder($database);
	$fld->load($folderID);

$database->setQuery("SELECT * FROM stavka WHERE id IN (".$ids.")");
$rows=$database->loadObjectList('id');

$database->execQuery("DELETE FROM sta_folder_stavka WHERE stavkaID IN ($ids) AND folderID=$folderID");
$cnt=$database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID ");
$res->javascript("changeCMVarByIndex($folderID,Ftree,3,$cnt);");

foreach($rows as $ix=>$row) {
		$LOG->savelogNOW("Uklanjaje stavke iz foldera",$row->naziv." /// folder '".$fld->naziv."'",'',$ix);
		if($pageact=='folder') $res->deleteRow("tbl_stavka",$ix);
		$res->deleteRow("tblF",$ix);
	}
 $database->execQuery("UPDATE sta_folder SET changed=NOW() WHERE id=$folderID");
?>