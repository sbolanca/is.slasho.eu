<?

include_once("opt/racun/racun.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM racun WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('racun','id',$ids);
		clearTable('racun_stavka','racunID',$ids);
		
		$logTitle="Brisanje računa";	
	$res->sound('beep');
	
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->code;
		$res->deleteRow("tbl_racun",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>