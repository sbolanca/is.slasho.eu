<?

include_once("opt/stavka/folder.class.php");	
	
	$rowp=new simStaFolder($database);
	$rowp->load($id);
	
	$row=new simStaFolder($database);

	$row->parentID=$id;
	$row->naziv=$id ? '- Novi podfolder -' : ' - Novi folder -';
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->check(true);
	$row->store();
	
	$LOG->savelogNOW("Novi podfolder","u '".$rowp->naziv."'",$id,$row->id);
	
	$res->addNode('Ftree',$row,$row->naziv);
	$res->nodeCM('Ftree',$row->id,$row->parentID.",$myID,0");
	$res->javascript("currentItem=".$row->id.";");
	$res->javascript("activateCMCommand('stavka','fld-edit');");

?>
