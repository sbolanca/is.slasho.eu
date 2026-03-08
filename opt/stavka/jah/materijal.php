<?

include_once("opt/stavka/stavka.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$val=intval(simGetParam($_POST,'val',0));

$database->setQuery("SELECT * FROM stavka WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

$database->execQuery("UPDATE stavka SET materijal=$val WHERE id IN ($ids)")	;	
	
		if($materijal) $logTitle="Stavka je materijalna";
		else $logTitle="Stavka nije materijalna";
	
	
	$list=array(); 
	foreach($rows as $ix=>$row) if(!($row->materijal==$val)) {
		$list[]=$row->id.": ".$row->naziv;
		if($val) $res->addRowClass("tbl_stavka",$ix,'own');
		else $res->removeRowClass("tbl_stavka",$ix,'own');
	
	}
	if(count($list)) $LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);
	
	$res->alertOK();

?>