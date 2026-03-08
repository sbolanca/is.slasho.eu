<?



function traziOsobe($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
	$ret['joinSQL']='';
	$ret['groupSQL']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','pre'));
	$dxs=simGetParam($_REQUEST,'selectItemdxs',$blank);
	$clanstvo=simGetParam($_REQUEST,'selectItemclanstvo',$blank);
	$filter=simGetParam($_REQUEST,'selectItemfilter',$blank);
	$deleted=intval(simGetParam($_REQUEST,'deleted',0));
	
	$groupBy=false;
	
	switch($deleted) {
		case 0: $where[]="(s.recyclebin=0)"; break;
		case 1: $where[]="(s.recyclebin=1)"; break;
		case 2: break;
	}
	$ret['ord']=$ord;
	
	
	if (!isset($_SESSION['tbl_user_fields'])) 
		$_SESSION['tbl_user_fields']=$SETTINGS['tbl_user_fields'];


	$ret['link']='';

	foreach($filter as $f)  $where[]="(s.$f>0)";

	
	switch ($ord) {
		//case 'pnd':  $ordering='s.ponder_time DESC,s.actstamp DESC,s.id';  $index='FORCE INDEX (po_ponderu)'; break;
	case 'sif':  $ordering='s.id';  break;
		case 'pre':  $ordering='s.prezime,s.ime';   break;
		case 'ime':  $ordering='s.ime,s.prezime';   break;
	}



	$sifra=trim(simGetParam($_REQUEST,'sifra',''));
	$naziv=trim(simGetParam($_REQUEST,'naziv',''));
	$email=trim(simGetParam($_REQUEST,'email',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	if ($sifra && intval($sifra)) { 
				$where[]="(s.id=".intval($sifra).")";
				$ret['index']=''; 
				$so[]='šifra: '.stripslashes(intval($sifra)); 
		
	}
		

	if ($naziv) { 
	//$ISRCQ=strlen(str_replace('-','',$user))==12? " OR REPLACE(s.ISRC,'-','')='".str_replace('-','',$user)."'":'';		
		$where[]="(s.name LIKE '$naziv%' OR s.name LIKE '% $naziv%' OR s.name LIKE '%-$naziv%')"; 
		$so[]='naziv: '.stripslashes($naziv).'* '; 
		//$ret['groupSQL']="GROUP BY s.id"; 
	}
	if ($email)  { 
		$where[]="s.email LIKE '%$email%'";  
		$so[]='email: *'.stripslashes($email).'* '; 
		//$ret['index']='USE INDEX (po_mbg_index)'; 
	}
	

		switch($pagetype) {
	

		case 'recyclebin': $ret['title']='IZBRISANE OSOBE'; break;
		case 'specijalniupit' : 
			$upitID=intval(simGetParam($_REQUEST,'upitID',0));
			$ret['link']="upitID=$upitID";
		
			$database->setQuery("SELECT * FROM specijalniupit WHERE id=$upitID");
			$database->loadObject($upit);
			if($upit->upit) $ret['joinSQL'].="\n INNER JOIN (\n".$upit->upit."\n) as q ON (".$upit->veza.") \n"; 
			if($upit->uvjet) $where[]="(".$upit->uvjet.")";
			if($upit->leftjoin) {
					$ret['joinSQL'].="\n ".$upit->leftjoin."\n";
					$groupBy=true;
			}
			$ret['title']='OSOBE: '.$upit->naziv; 
			if($upit->opis) $so[]=' (*** '.$upit->opis.' ***)';
			
			break;
		
		default: $ret['title']='DJELATNICI';
	}
	
		
	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			
	//$fp=fopen("sql.txt","w");fputs($fp,$ret['whereSQL']);fclose($fp);

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM user as s ".$ret['index']." ".$ret['joinSQL']." ".$ret['whereSQL']);
	//echo $database->_sql;
	{$fp=fopen("sqlu.txt","w");fputs($fp,$database->_sql.";\n");fclose($fp); }
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['userSQL']=$ret['joinSQL']." ".$ret['whereSQL']; 
	$_SESSION['userOrderingSQL']=$ret['orderingSQL']; 
	$_SESSION['userINDEX']=$ret['index']; 
	$_SESSION['userTITLE']=$ret['title']; 
	$_SESSION['userSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

?>