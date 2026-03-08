<?

include_once("opt/mail/mail.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simMail($database);
if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();
} else {
	$row->emfrom=$simConfig_mailfrom;
	$row->emfromname=$simConfig_fromname;
	$row->html=0;
}
	$tmpl->readTemplatesFromInput( "opt/mail/jah/edit.html");
	$tmpl->addVar("opt_mail", "chkhtml".$row->html,'checked');
	$tmpl->addObject("opt_mail", $row, "row_",true);
	$tmpl->addVar("opt_mail", "MYEMAIL",$database->getResult("SELECT email FROM user WHERE id=".$myID));
	$tmpl->addVar("opt_mail", "MYNAME",$database->getResult("SELECT name FROM user WHERE id=".$myID));
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi mail predložak":"Mail predložak: ".$id),$cont,650,2,'blue-light');


?>