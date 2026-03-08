<?

$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$database->setQuery("SELECT * FROM user WHERE id IN (".$ids.")");
$rows=$database->loadObjectList('id');
	
	$database->execQuery("UPDATE user SET recyclebin=1 WHERE id IN ($ids)");
	
foreach($rows as $ix=>$row) {
	$res->deleteRow("tbl_user",$ix);
	
	$LOG->savelogNOW("Brisanje korisnika",$row->name,exportObject($row),$ix);
}
	if (!$hideMSG) $res->alert('Izbrisano!');

	$res->sound('beep');
?>