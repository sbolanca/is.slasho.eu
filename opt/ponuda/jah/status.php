<?

include("opt/ponuda/ponuda.class.php");


$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));
$status=intval(simGetParam($_REQUEST,'status',0));




$database->setQuery("SELECT * FROM ponuda WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

$database->execQuery("UPDATE ponuda SET status=$status WHERE id IN ($ids)");

	$logTitle="Status ponude: ";

	$logTitle.=getPonudaStatusTitle($status);
	
	
	$list=array();
	foreach($rows as $ix=>$row) if(!($status==$row->status)) {
		$list[]=$row->id.": ".$row->code;
		if(intval($row->status)) $res->removeRowClass("tbl_ponuda",$ix,getPonudaStatusCls($row->status));
		if($status) $res->addRowClass("tbl_ponuda",$ix,getPonudaStatusCls($status));
		$res->rowCM("tbl_ponuda",$ix,$row->klijentID.",$status");
	}
	if(count($list)) $LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);
	$res->alertOK();
?>