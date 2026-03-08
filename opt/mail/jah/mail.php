<?

include_once("opt/mail/mail.class.php");
$ids=trim(simGetParam($_REQUEST,'ids',$id));
$idsA=explode("|",$ids);

$database->setQuery("SELECT *,IF(id='$id','selected','') as sel FROM mail ORDER BY id");

	$trows=$database->loadObjectList();

	$row=new simMail($database);
if($id) {	
	$row->load($id);
	//$row->htmlspecialchars();
	
} else {
	$row->emfrom=$simConfig_mailfrom;
	$row->emfromname=$simConfig_fromname;
}
	$tmpl->readTemplatesFromInput( "opt/mail/jah/mail.html");
	$tmpl->addObject("opt_mail", $row, "row_",true);
	$tmpl->addObject("opt_mail_t", $trows, "row_",true);
	$tmpl->addVar("opt_mail", "ids",$ids);
	$tmpl->addVar("opt_mail", "chkhtml".$row->html,'checked');
	$tmpl->addVar("opt_mail", "MYEMAIL",$database->getResult("SELECT email FROM user WHERE id=".$myID));
	$tmpl->addVar("opt_mail", "MYNAME",$database->getResult("SELECT name FROM user WHERE id=".$myID));
	$tmpl->addVar("opt_mail", "cnt",count($idsA));
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	
	$res->openSimpleDialog("Pošalji mail",$cont,650,4,'blue-full');


?>