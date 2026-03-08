<?

include_once("opt/folder/folder.class.php");	
	
	$row=new simFolder($database);
	$row->bind($_POST);
	$row->check(true);
	$row->store();
	
	$row->load();
	
	$res->javascript("hideStandardPopup('editbox');");
	$res->changeNode('Ftree',$row,$row->naziv);
	
	$LOG->savelog("Izmjena mape",$row->naziv, $row->export("NOVI PODACI:"));

	
?>
