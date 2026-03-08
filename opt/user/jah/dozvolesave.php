<?
	$row=new simUser($database);
	$row->load($id);

	$username=trim(simGetParam($_REQUEST,'username',$id));
	if($username) {
		$database->execQuery("DELETE FROM permissions WHERE username='$username'");
		
		$pArr=array();
		
		foreach($_POST as $k=>$v) if ($v && (substr($k,0,2)=='o_')) { 
			$o=substr($k,2);
			$database->execQuery("INSERT INTO permissions (username,opt,permission) VALUES "
			." ('$username','".$o."','$v')");
			$pArr[]=$o.'['.$v.']';
		}
			$res->javascript("hideStandardPopup('editbox');");	
		
			$ovlasti=implode(',',$pArr);
			$res->changeCellValueByCode('tbl_user',$id,'permissions',$ovlasti);
			$LOG->savelog("Izmjena ovlasti korisnika",$row->name,$ovlasti);
	} else $res->alert("Ne možete postavljati dozvole korisniku koji nema korisničko ime.");

?>