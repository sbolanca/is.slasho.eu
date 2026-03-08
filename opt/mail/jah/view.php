<?

include_once("opt/mail/mail.class.php");


	$row=new simMailSent($database);
	$row->load($id);
	
	
	$row->addField("user,sender,template,fdate,mbg");
	$row->mbg=$database->getResult("SELECT mbg FROM user WHERE id=".$row->userID);
	$row->user=$database->getResult("SELECT name FROM user WHERE id=".$row->userID);
	$row->sender=$database->getResult("SELECT name FROM user WHERE id=".$row->senderID);
	if($row->mailID) $row->template=$database->getResult("SELECT title FROM mail WHERE id=".$row->mailID);
	$row->fdate=convertSQLDateTimeToHr($row->sent_date);
	
	$html=intval($database->getResult("SELECT html FROM mail WHERE id=".$row->mailID));
	if(!$html) $row->body=nl2br($row->body);

	$tmpl->readTemplatesFromInput( "opt/mail/jah/view.html");
	$tmpl->addObject("opt_mail", $row, "row_",true);
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	$res->openSimpleDialog("Poslani mail",$cont,650,4,'blue-light');


?>