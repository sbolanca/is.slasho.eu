<?

include_once("opt/klijent/klijent.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM klijent WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('klijent','id',$ids);
	
		$logTitle="Brisanje klijenta";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_klijent",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>