<?
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

	clearTable('log','id',$ids);
	
	foreach (explode(",",$ids) as $ix) $res->deleteRow("tbl_log",$ix);

?>