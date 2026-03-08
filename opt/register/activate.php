<?

$code=trim(simGetParam($_REQUEST,'code',''));

$row=new simUser($database);
$row->load($id);
if (!$row->active) {
	if ($code==$row->password) {
	
		$row->active=1;
	
		$row->store();
		
		global $simConfig_mailfrom,$simConfig_fromname,$simConfig_sitename,$simConfig_live_site,$Register;
		require_once( "inc/phpmailer/class.phpmailer.php" );
		
		$subject=_USER_AMAIL_SUBJECT." : ".$row->name;
		
		$body=_USER_AMAIL_INTRO;
		$body.="\r\n------------------------------------";
		$body.="\r\n"._USER_NAME.": ".$row->name;
		$body.="\r\n"._USER_USERNAME.": ".$row->username;
		$body.="\r\n"._USER_EMAIL.": ".$row->email;
		$body.="\r\n"._USER_PHONENUM.": ".$row->phone;
		$body.="\r\n"._USER_COUNTRY.": ".$row->country;
		$body.="\r\n"._USER_PREFEREDCONTACT.": ".($row->emlcontact ? strtolower(_USER_EMAIL) : strtolower(_USER_PHONE));
		$body.="\r\n------------------------------------";
	
		
		simMail($simConfig_mailfrom,$simConfig_sitename,$simConfig_mailfrom,$subject,$body);
	
		$Register->loginByCode( $id,$code,"index.php?lang=$lang");	
		
		die();

	}	
} else {
	$tmpl->addVar("opt_register","MESSAGE",_USER_ALREADY_ACTIVATED);
}




?>