<?

$folderID=intval(simGetParam($_REQUEST,'folderID',0));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

	$database->execQUery("DELETE FROM sta_folder_stavka WHERE folderID=$folderID AND stavkaID IN ($ids)");
	$cnt=$database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID ");
	$database->execQuery("UPDATE sta_folder SET changed=NOW() WHERE id=$folderID");
	$res->javascript("changeCMVarByIndex($folderID,Ftree,3,$cnt);");
	$res->sound('beep');
	foreach (explode(",",$ids) as $idr) $res->deleteRow('tblF',$idr);
	$res->javascript('lastOpenFolder=0');


?>
