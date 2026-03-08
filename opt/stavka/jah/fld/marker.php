<?
$value=intval(simGetParam($_REQUEST,'value',0));	
$folderID=intval(simGetParam($_REQUEST,'folderID',0));	
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));	

	
	
	$database->setQuery("UPDATE sta_folder_stavka SET marker=$value WHERE folderID=$folderID AND stavkaID IN ($ids)");
	$database->query();
	$database->execQuery("UPDATE sta_folder SET changed=NOW() WHERE id=$folderID");
	switch ($value) {
		case 1: $col="#0000cc"; break;
		case 2: $col="#00aa00"; break;
		case 3: $col="#cc0000"; break;
		case 4: $col="#777700"; break;
		case 5: $col="#a800c1"; break;
		default: $col="#000000";
	}
	foreach (explode(",",$ids) as $idr) $res->markRow("tbl_stavka",$idr,'',$col);


?>