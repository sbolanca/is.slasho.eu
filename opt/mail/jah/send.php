<?

include_once("opt/mail/mail.class.php");
$test=trim(simGetParam($_REQUEST,'test',0));
$ids=trim(simGetParam($_REQUEST,'ids',$id));
$idsA=explode("|",$ids);

$database->setQuery("SELECT * FROM user WHERE id IN (".str_replace("|",",",$ids).")");
$rows=$database->loadObjectList();


$templateMailName=$database->getResult("SELECT title FROM mail WHERE id=".$id);

	$mail=new simMail($database);
	$mail->bind($_POST);
	$mail->check();
	if(!$mail->emfrom) $row->emfrom=$simConfig_mailfrom;
	if(!$mail->emfromname) $row->emfromname=$simConfig_fromname;
	
	$failed=array();$failed=array();
	
	$myMail=$database->getResult("SELECT email FROM user WHERE id=".$myID);
	
foreach($rows as $row) {
	$subject=$mail->subject;
	$body=$mail->body;
	foreach(get_object_vars($row) as $n=>$v) {
			$subject=str_replace("«".strtoupper($n)."»",$row->$n,$subject);
			$body=str_replace("«".strtoupper($n)."»",$row->$n,$body);
	}
	$emto=$test?$myMail:$row->email;
	
	
	$ms=new simMailSent($database);
	$ms->userID=$row->id;
	$ms->senderID=$myID;
	$ms->email=$emto;
	$ms->mailID=$mail->id;
	$ms->sent_date=date("Y-m-d H:i:s");
	$ms->subject=$subject;
	$ms->body=$body;
	
	if (simMail($mail->emfrom,$mail->emfromname,$emto,$subject,$body,$mail->html)) {
	//if (simMail($mail->emfrom,$simConfig_fromname,'slaven@kampanel.com',$subject,$body,$mail->html)) {
			$ms->failed=0;
			if(!$test) $LOG->createTblLog('izvodjac',$row->id,"Slanje maila po templateu",$templateMailName);
			
	} else {
		$ms->failed=1;
		$failed[]=$row->name." [".$row->email."]";
	}
	
	if(!$test) $ms->store();
	if(!$ms->failed) $list[]="<span class=\"blue ptr\" onClick=\"aCMC(".$ms->id.",'mail','view')\">".$row->name." [".$row->email."]</span>";
}	
	if(!$test) {
		if(count($list)) $LOG->saveIlog(1,"Slanje maila po templateu",getMultiTitle($rows,$rows[0]->name),"Template: $templateMailName\n\nPrimatelji:\n".implode("\n",$list).(count($failed)?"\n\nNespješno slanje:\n".implode("\n - ",$failed):"")."\n\n".$mail->export(),$id,false);
		else if(count($failed)) $LOG->saveIlog(1,"Slanje maila po templateu",getMultiTitle($rows,$rows[0]),"Template: $templateMailName\n\nNeuspješno slanje:\n".implode("\n - ",$failed)."\n\n".$mail->export(),$id,false);
	}
	$res->closeSimpleDialog(4);
	$res->javascript("canRefresh=true;");
	$res->alert("Poslano ".(count($rows)-count($failed))." mailova.");
	if(count($failed)) $res->alert("Pogreška pri slanju maila:\n".implode("\n - ".$failed));


?>