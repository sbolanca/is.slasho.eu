<?
$row=new simUser($database);
$row->load($id);

//$password=md5(trim(simGetParam($_REQUEST,'password','')));
$password=trim(simGetParam($_REQUEST,'password',''));

$database->setQuery("UPDATE user SET password='$password' WHERE id=$id");
$database->query();
	
$res->javascript("hideStandardPopup('editbox');");
$res->alert("Lozinka je promjenjena");

	$LOG->savelog("Izmjena korisnikove lozinke",$row->name,trim(simGetParam($_REQUEST,'password','')));


?>