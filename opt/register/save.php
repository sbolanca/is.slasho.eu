<?

$isNew=($id ? false : true);

$row=new simUser($database);
if (!$isNew) {
	$row->load($id);
	$oldpass=$row->password;
	$row->bind($_POST);
	if ($row->password) $row->password=md5($row->password);
	else $row->password=$oldpass;
	}
else {
	$row->bind($_POST);
	//$row->active=1;
	$pass=$row->password;
	$row->password=md5($row->password);
	
	
	
	
	}
$row->store();
$_SESSION['aktualno']=$row->aktualno;
$_SESSION['colorset']=$row->colorset;
$_SESSION['MM_name']=$row->name;

			

if ($isNew) {
	$msg=_USER_MESS_OK_NEW;
	
	global $simConfig_mailfrom,$simConfig_fromname,$simConfig_sitename,$simConfig_live_site;
	require_once( "inc/phpmailer/class.phpmailer.php" );
	
	$subject=_USER_CLMAIL_SUBJECT;
	
	$body=_USER_CLMAIL_INTRO;
	$body.="\r\n------------------------------------";
	$body.="\r\n"._USER_NAME.": ".$row->name;
	$body.="\r\n"._USER_EMAIL.": ".$row->email;
	$body.="\r\n"._USER_PHONENUM.": ".$row->phone;
	$body.="\r\n"._USER_COUNTRY.": ".$row->country;
	$body.="\r\n"._USER_PREFEREDCONTACT.": ".($row->emlcontact ? strtolower(_USER_EMAIL) : strtolower(_USER_PHONE));
	$body.="\r\n------------------------------------";
	$body.="\r\n"._USER_USERNAME.": ".$row->username;
	$body.="\r\n"._USER_PASSWORD.": ".$pass;
	$body.="\r\n------------------------------------";
	$body.="\r\n"._USER_CLMAIL_ACTIVATION_LINK;
	$body.="\r\n".$simConfig_live_site."/index.php?opt=register&act=activate&lang=".$lang."&id=".$row->id."&code=".$row->password;

	
	simMail($simConfig_mailfrom,$simConfig_sitename,$row->email,$subject,$body);

	
	
} else  {
	$msg=_USER_MESS_OK_OLD;
	$LOG=new simLog($database,'register','save',$row->id,$mainFrame->adminId,$mainFrame->podruznicaID,$mainFrame->isSuper);		
	$LOG->savelog("Izmjena korisničkog profila",$_SESSION['MM_name'],$row->export());
	$LOG->check();
	$LOG->store();

}

$tmpl->addVar("opt_register","_USER_REGISTER",_USER_REGISTER);
$tmpl->addVar("opt_register","MESSAGE",$msg);



?>