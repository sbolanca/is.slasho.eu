<?



	$row=new simUser($database);
	$row->bind($_POST);
	//$row->password=$row->password?md5(trim($row->password)):null;
	
	$row->password=trim($row->password)?trim($row->password):null;
	$row->name=trim($row->ime." ".$row->prezime);
	$isOld=intval($row->id);
	
	
	
	$row->check(true);
	$row->store();

	$res->javascript("hideStandardPopup('editbox');");	
	
	$color=intval($row->active) ? '#000000' : '#666666';
	
	if ($isOld)	{
		$res->changeRowValues('tbl_user',$row,$_SESSION['tbl_user_fields']);
		$LOG->saveIlog(1,"Izmjena podataka o korisniku",$row->name,$row->export());

	} else {
		$res->addRow('tbl_user',$row,$_SESSION['tbl_user_fields']);
		$LOG->saveIlog(1,"Dodavanje novog korisnika",$row->name,$row->export(),$row->id);

	}
	$res->markRow("tbl_user",$row->id,"",$color);
		
?>