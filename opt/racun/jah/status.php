<?

include("opt/racun/racun.class.php");


$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$status=intval(simGetParam($_REQUEST,'status',0));




$database->setQuery("SELECT * FROM racun WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

$database->execQuery("UPDATE racun SET status=$status WHERE id IN ($ids)");

	$logTitle="Status računa: ";

	$logTitle.=getRacunStatusTitle($status);
	
	
	$list=array();
	foreach($rows as $ix=>$row) if(!($status==$row->status)) {
		$list[]=$row->id.": ".$row->code;
		if(intval($row->status)) $res->removeRowClass("tbl_racun",$ix,getRacunStatusCls($row->status));
		if($status) $res->addRowClass("tbl_racun",$ix,getRacunStatusCls($status));
		$res->rowCM("tbl_racun",$ix,$row->klijentID.",$status");
	}
	if(count($list)) $LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);
	$res->alertOK();
?>