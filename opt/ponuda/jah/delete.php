<?

include_once("opt/ponuda/ponuda.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM ponuda WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('ponuda','id',$ids);
		clearTable('ponuda_stavka','ponudaID',$ids);
		
		$logTitle="Brisanje ponude";	
	$res->sound('beep');
	
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->code;
		$res->deleteRow("tbl_ponuda",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>