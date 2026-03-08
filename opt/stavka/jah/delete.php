<?

include_once("opt/stavka/stavka.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM stavka WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('stavka','id',$ids);
	
		$logTitle="Brisanje stavke";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->code;
		$res->deleteRow("tbl_stavka",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>