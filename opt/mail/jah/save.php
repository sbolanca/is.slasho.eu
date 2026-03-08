<?

include_once("opt/mail/mail.class.php");



$row=new simMail($database);
$row->bind($_POST);
$isOld=intval($row->id);
if(!$isOld) $row->created=date("Y-m-d H:i:s");
$row->check(true);


	$row->store();
	

	
	$LOG->saveIlog(1,$isOld?"Izmjena mail predloška":"Dodavanje novog mail predloška",$row->title,$row->export(),$id,false);
	
	$res->closeSimpleDialog(2);
	
	
	
	
	if($pageopt=='mail') {
		$row->load($row->id);
		$body=strip_tags($row->body);
		$row->body=str_replace("\r"," ",str_replace("\n"," ",(strlen($body)>200)?substr($body,0,200)."...":$body));
		$row->addField('fcreated');
		$row->fcreated=$isOld?convertSQLDateTimeToHr($row->created):date("H:i");
	
		if($isOld) $res->changeRowValues('tbl_mail',$row,$_SESSION['tbl_mail_fields']);
		else	$res->addRow('tbl_mail',$row,$_SESSION['tbl_mail_fields'],'0');
	}		


?>