<?

include_once("opt/mail/mail.class.php");


	$row=new simMailSent($database);
	$row->load($id);
	
	
	$row->failed=1-intval($row->failed);
	
	$database->execQuery("UPDATE mail_sent SET failed=1-failed WHERE id=$id");
	
	if(intval($row->failed)) $res->javascript("$('#sntm_$id').addClass('sukob');");
	else $res->javascript("$('#sntm_$id').removeClass('sukob');");


?>