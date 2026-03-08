<?
$parentID=intval(simGetParam($_REQUEST,'parentID',0));

include_once("opt/stavka/folder.class.php");	

	if ($parentID) {
			$database->setQuery("SELECT naziv FROM sta_folder WHERE id=$id");
			$naziv="'".$database->loadResult()."'";
	} else $naziv="_root";

	
	$row=new simStaFolder($database);
	$row->parentID=$parentID;
	$row->naziv=$parentID ? '- Novi podfolder -' : ' - Novi folder -';
	$row->userID=$myID;
	$row->created=date("Y-m-d H:i:s");
	$row->check(true);
	$row->store();
	
	$LOG->savelogNOW("Novi podfolder"," u $naziv",$parentID,$row->id);

	
	$res->addNode('Ftree',$row,$row->naziv);
	$res->nodeCM('Ftree',$row->id,$row->parentID.",$myID,0");
	$res->javascript("currentItem=".$row->id.";");
	$res->javascript("activateCMCommand('stavka','fld-edit');");

?>
