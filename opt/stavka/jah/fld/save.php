<?

include_once("opt/stavka/folder.class.php");	
	
	$row=new simStaFolder($database);
	$row->bind($_POST);
	$row->check(true);
	$row->store();
	
	$row->load();
	
	$res->javascript("hideStandardPopup('editbox');");
	$res->changeNode('Ftree',$row,addslashes($row->naziv));
	
	$LOG->savelog("Izmjena foldera stavke",$row->naziv, $row->export("NOVI PODACI:"));

	
?>
