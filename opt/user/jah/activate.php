<?
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$database->execQuery("UPDATE user SET active=1-active WHERE id IN (".$ids.")");

$database->setQuery("SELECT id,active,name FROM user WHERE id IN (".$ids.")");
$rows=$database->loadObjectList('id');

foreach($rows as $ix=>$row) {
	$ispis=intval($row->active) ? 'AKTIVIRAN' : 'DEAKTIVIRAN';
	$color=intval($row->active) ? '#000000' : '#666666';
	$res->markRow("tbl_user",$ix,"",$color);
	
	$LOG->savelogNOW("Promjena statusa korisnika",$row->name." -> ".$ispis,'',$ix);
}
$res->sound('select');
$res->alert('Status korisnika je promjenjen');

?>