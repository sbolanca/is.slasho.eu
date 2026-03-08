<?
$ids=$_SESSION['sta_clipboard'];
$_SESSION['sta_clipboard']='';

$res->javascript("clipboard=[];$('#clipboard').html('0');");

$res->sound('beep');
if($ids) {
	$database->setQuery("SELECT * FROM stavka WHERE id IN ($ids)");
	$rows=$database->loadObjectList();
	$list=array();
	foreach($rows as $row) {
		$list[]=$row->id."| ".$row->naziv;

	}
	$LOG->saveIlog(0,"Pražnjenje spremnika stavki",count($rows),implode("\n",$list),0,false);
}

?>