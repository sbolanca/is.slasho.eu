<?

$folderID=intval(simGetParam($_REQUEST,'folderID',0));
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

	$database->execQUery("DELETE FROM folder_arhiva WHERE folderID=$folderID AND arhivaID IN ($ids)");
	
	
	foreach (explode(",",$ids) as $idr) $res->deleteRow('tblF',$idr);

?>
