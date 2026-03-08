<?

include_once("opt/kategorijastavke/kategorijastavke.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM kategorijastavke WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('kategorijastavke','id',$ids);
	
		$logTitle="Brisanje kategorije stavke";	
	
	$res->sound('beep');
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->id.": ".$row->naziv;
		$res->deleteRow("tbl_kategorijastavke",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>