<?
$parentID=intval(simGetParam($_REQUEST,'parentID',0));

include_once("opt/folder/folder.class.php");	

	if ($parentID) {
			$database->setQuery("SELECT naziv FROM folder WHERE id=$id");
			$naziv="'".$database->loadResult()."'";
	} else $naziv="_root";

	
	$row=new simFolder($database);
	$row->parentID=$parentID;
	$row->naziv=$parentID ? '- Nova podmapa -' : ' - Nova mapa -';
	$row->userID=$myID;
	$row->check(true);
	$row->store();
	
	$LOG->savelogNOW("Nova podmapa"," u $naziv",$parentID,$row->id);

	
	$res->addNode('Ftree',$row,$row->naziv);
	$res->rowCM('Ftree',$row->id,$row->parentID.",$myID,0");
	$res->javascript("currentItem=".$row->id.";");
	$res->javascript("lastOpenMap=".$row->id.";");
	$_SESSION['lastOpenMap']=$row->id;
	$res->javascript("activateCMCommand('folder','fld-edit');");
	$res->javascript("Ftree.selectItem(lastOpenMap,0,0);");
	
?>
