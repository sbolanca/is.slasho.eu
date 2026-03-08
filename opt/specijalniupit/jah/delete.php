<?

include_once("opt/specijalniupit/specijalniupit.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM specijalniupit WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('specijalniupit','id',$ids);
	
		$logTitle="Brisanje specijalnog upita";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_specijalniupit",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>