<?

include_once("opt/folder/folder.class.php");	
	
	$rowp=new simFolder($database);
	$rowp->load($id);
	
	$row=new simFolder($database);

	$row->parentID=$id;
	$row->naziv=$id ? '- Nova podmapa -' : ' - Nova mapa -';
	$row->userID=$myID;
	$row->check(true);
	$row->store();
	
	$LOG->savelogNOW("Nova podmapa","u '".$rowp->naziv."'",$id,$row->id);
	
	$res->addNode('Ftree',$row,$row->naziv);
	$res->rowCM('Ftree',$row->id,$row->parentID.",$myID,0");
	$res->javascript("currentItem=".$row->id.";");
	$res->javascript("activateCMCommand('folder','fld-edit');");
	$res->javascript("lastOpenMap=".$row->id.";");
	$_SESSION['lastOpenMap']=$row->id;
	$res->javascript("lastOpenMap=".$row->id.";");
	$res->javascript("Ftree.selectItem(lastOpenMap,0,0);");
	
?>
