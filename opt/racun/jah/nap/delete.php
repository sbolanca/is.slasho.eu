<?

include_once("opt/racun/racun.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM racun_napomena WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('racun_napomena','id',$ids);
	
		$logTitle="Brisanje napomene računa";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_racunnapomena",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>