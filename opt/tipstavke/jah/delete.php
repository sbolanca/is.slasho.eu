<?

include_once("opt/tipstavke/tipstavke.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM tipstavke WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('tipstavke','id',$ids);
	
		$logTitle="Brisanje tipa stavke";	
	
	
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_tipstavke",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);

	$res->sound('beep');
?>