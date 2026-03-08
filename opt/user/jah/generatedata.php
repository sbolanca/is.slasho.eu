<?
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$subjectTMPL='ONLINE HUZIP pristupni podaci za «NAME»';
$bodyTMPL=file_get_contents('mail_templates/pristupni_podaci.html');

$database->setQuery("SELECT * FROM user WHERE id IN (".$ids.")");
$rows=$database->loadObjectList('id');
$acnt=0;$ucnt=0;$pcnt=0;$ecnt=0;$ocnt=0;$failed=array();
foreach($rows as $ix=>$row) {
	$sqlset=array();
	if (!intval($row->active) && in_array('act',$selectedActions)) {
		$acnt++;
		$row->active=1;
		$res->markRow("tbl_user",$row->id,"",'#000000');
		$sqlset[]="active=1";
	} else $row->active=null;
	if(in_array('unamenew',$selectedActions) || (!trim($row->username) && in_array('uname',$selectedActions))) {
		$x=0; $ucnt++;
		$row->username='';
		while (!trim($row->username) && ($x<10)) {
			$row->username=generateUsername($row->ime,$row->prezime,$x);
			if ($database->getResult("SELECT COUNT(id) FROM user WHERE id<>$ix AND username='".$row->username."'")) {
				$row->username=''; $x++;
			} 
		}
		$res->changeCellValueByCode("tbl_user",$ix,'username',$row->username);
		$sqlset[]="username='".$row->username."'";
		$uname=$row->username;
	} else $uname='';
	if(in_array('passnew',$selectedActions) || (!trim($row->password) && in_array('uname',$selectedActions))) {
		$pcnt++;
		$row->password=generatePassword();
		
		$sqlset[]="password='".$row->password."'";
	};
	
	if(trim($row->email)  && in_array('email',$selectedActions)) {
		$subject=$subjectTMPL;
		$body=$bodyTMPL;
		foreach(get_object_vars($row) as $n=>$v) {
			$subject=str_replace("«".strtoupper($n)."»",$row->$n,$subject);
			$body=str_replace("«".strtoupper($n)."»",$row->$n,$body);
		}
		if (simMail($simConfig_mailfrom,$simConfig_fromname,$row->email,$subject,$body,1)) {
			$row->sadrzaj_emaila=$body;
			$ecnt++;
			$LOG->saveIlogNOW(1,"Slanje maila sa pristupnim podacima",$row->name." [".$row->email."]",$body,$ix);
		} else $failed[]=$row->name." [".$row->email."]";
		
	}
	$row->ime=null;$row->prezime=null;
	if(!$uname) $row->username=null;
	
	if (count($sqlset)) {
		$ocnt++;
		$database->execQuery("UPDATE user SET ".implode(",",$sqlset)." WHERE id=$ix");	
		$LOG->saveIlogNOW(1,"Generiranje pristupnih podataka",$row->name,exportObject($row),$ix);
	}
}
$res->alert("Obrađeno $ocnt/".count($rows)." korisinika.<br>Aktivirano $acnt korisnika.<br>Generirano $ucnt korisničkih imena.<br>Generirano $pcnt lozinki.<br>Poslano $ecnt mailova sa korisničkim podacima.".
	(count($failed)?'<br><br>- '.implode('<br>- ',$failed):''));
	
	
		
?>