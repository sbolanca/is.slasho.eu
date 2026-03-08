<?

include_once("opt/mail/mail.class.php");

$failed=intval(simGetParam($_REQUEST,'failed',0));

$row=new simMail($database);
$row->load($id);
$database->setQuery("SELECT m.*,u.naziv,u2.naziv as sender,IF(m.failed=1,'sukob','') as cls FROM mail_sent AS m"
." LEFT JOIN user AS u ON u.id=m.userID"
." LEFT JOIN user AS u2 ON u2.id=m.senderID"
." WHERE m.mailID=$id ".($failed?" AND m.failed=1":'')." ORDER BY m.sent_date DESC");

	$rows=$database->loadObjectList();

	$tmpl->readTemplatesFromInput( "opt/mail/jah/sentlist.html");
	$tmpl->addObject("opt_mail", $row, "row_",true);
	$tmpl->addObject("opt_mail_l", $rows, "row_",true);
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	
	$res->openSimpleDialog("Poslani mailovi za template: ".$row->title,$cont,985,2,'yellow-light');


?>