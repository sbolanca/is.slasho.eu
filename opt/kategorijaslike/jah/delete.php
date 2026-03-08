<?

include_once("opt/kategorijaslike/kategorijaslike.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM kategorijaslike WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('kategorijaslike','id',$ids);
	
		$logTitle="Brisanje kategorije slike";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_kategorijaslike",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>