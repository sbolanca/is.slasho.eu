<?

function traziLogove($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		

	$log_app=simGetParam($_REQUEST,'selectItemlog_app',$blank);
	

	$ret['log_app']=$log_app;
	
	


	if (!isset($_SESSION['tbl_log_fields'])) 
		$_SESSION['tbl_log_fields']=$SETTINGS['tbl_log_fields'];


	$log_nofilter=intval(simGetParam($_REQUEST,'log_nofilter',0));
	$log_IP=trim(simGetParam($_REQUEST,'log_IP',''));
	$log_subject=trim(simGetParam($_REQUEST,'log_subject',''));
	$log_user=trim(simGetParam($_REQUEST,'log_user',''));
	$ret['log_userID']=intval(simGetParam($_REQUEST,'log_userID',0));
	$log_optact=trim(simGetParam($_REQUEST,'log_optact',''));
	$log_dbindex=trim(simGetParam($_REQUEST,'log_dbindex',''));
	$log_details=trim(simGetParam($_REQUEST,'log_details',''));
	$log_title=trim(simGetParam($_REQUEST,'log_title',''));
		
	$ret['link']='opt=log&act='.$pagetype;

	$ret['title']='LOGOVI';

	if ($log_title) {
		$where[]="s.title LIKE '%$log_title%'";  
		$so[]='akcija: *'.$log_title."*";
	}
	if ($log_subject) {
		$where[]="s.subject LIKE '%$log_subject%'";  
		$so[]='subjekt: *'.$log_subject."*";
	}
	if ($log_details) {
		$where[]="s.details LIKE '%$log_details%'";
		$so[]='detalj: *'.$log_details."*";		
	}
	if ($log_user) {
		//$where[]="s.userID=$log_user";  
		//$so[]='korisnikID: '.$log_user;
		$where[]="(s.user LIKE '$log_user%' OR s.user LIKE '% $log_user%')"; 
		$so[]='korisnik: '.$log_user."*";
		$ret['log_userID']=0;
	}
	if ($ret['log_userID']) {
		$where[]="s.userID=".$ret['log_userID'];  
		$so[]='korisnikID: '.$ret['log_userID'];
		$ret['title']='LOGOVI: '.$database->getResult("SELECT name FROM user WHERE id=".$ret['log_userID']);		
	}
	if ($log_IP) {
		$where[]="s.ip='$log_IP'";
		$so[]='IP: '.$log_IP;
	}
	if ($log_optact) {
			$oa=explode("|",$log_optact);
			$where[]="s.opt='".$oa[0]."'";
			$so[]='opt: '.$oa[0];
			if (count($oa)>1) {
				$where[]="s.act='".$oa[1]."'";
				$so[]='act: '.$oa[1];
			}
		} 
	if (count($log_app)) {
		$where[]="(s.app IN ('".implode("','",$log_app)."'))";
		$so[]='aplikacija: '.implode("','",$log_app);
	}
	if (!$log_nofilter) {
		$where[]="s.importance>1";  
	} else $so[]='detaljan ispis';
	

	
	
		
	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY s.id DESC";


	$database->setQuery("SELECT COUNT(s.id) FROM log as s ".$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['SQL']=$ret['whereSQL'].$ret['orderingSQL']; 
	$_SESSION['INDEX']=$ret['index']; 
	$_SESSION['TITLE']=$ret['title']; 
	$_SESSION['SO']=$ret['searchoptions']; 
	
	
	return $ret;

}



?>