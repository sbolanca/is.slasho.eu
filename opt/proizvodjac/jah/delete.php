<?

include_once("opt/proizvodjac/proizvodjac.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM proizvodjac WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('proizvodjac','id',$ids);
	
		$logTitle="Brisanje proizvođača uređaja";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_proizvodjac",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>