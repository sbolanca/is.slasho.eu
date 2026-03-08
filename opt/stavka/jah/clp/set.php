<?


$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$_SESSION['sta_clipboard']=$ids;

$res->javascript("clipboard=[$ids];$('#clipboard').html(clipboard.length);");

$res->sound('select');

$database->setQuery("SELECT * FROM stavka WHERE id IN ($ids)");
$rows=$database->loadObjectList();
$list=array();
foreach($rows as $row) {
	$list[]=$row->id."| ".$row->naziv;
	$LOG->createTblLog('stavka',$row->id,"Stavka u spremnik",'');

}
$LOG->saveIlog(0,"Stavka u spremnik",getMultiTitle($rows,getRowFullTitle($rows[0])),implode("\n",$list),$id,false);

?>