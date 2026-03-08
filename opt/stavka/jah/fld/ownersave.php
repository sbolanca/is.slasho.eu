<?

include_once("opt/stavka/folder.class.php");	
	
	$row=new simStaFolder($database);
	$row->bind($_POST);
	$row->store();
	
	$row->load();
	
	
	$res->javascript("hideStandardPopup('editbox2');");
	
	$owner=$database->getResult("SELECT name FROM user WHERE id=".$row->userID);
	
	if($row->userID==$myID) $res->alert("Folder je u vašem vlasništvu.");
	else {
		if(($row->sharing==5) || (($row->sharing==4) && in_array($myID,explode(",",$row->visibility)))) {
			$prikaz="biti prikazan na odgovarajući način";
		} else $prikaz="neće vam biti prikazan jer vam nije dodjeljen na nijedan način";
	
		$res->alert("Novi vlasnik foldera je ".$owner.".\n\nNakon refresha stranice folder će prihvatiti novo vlasništvo i $prikaz.\n\nTada više nećete biti u mogućnosti mijenjati vlasnika ni određivati vidljivost ovog foldera.");
	}
	$LOG->savelog("Izmjena vlasnika foldera",$row->naziv, $owner);
	
?>
