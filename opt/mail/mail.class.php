<?

function traziMailove($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','sif'));
	
	$ret['ord']=$ord;
	
	


	if (!isset($_SESSION['tbl_mail_fields'])) 
		$_SESSION['tbl_mail_fields']=$SETTINGS['tbl_mail_fields'];


	$ret['link']='opt=mail&act='.$pagetype;

	switch ($ord) {
		//case 'pnd':  $ordering='s.ponder_time DESC,s.actstamp DESC,s.id';  $index='FORCE INDEX (po_ponderu)'; break;
		case 'sif':  $ordering='s.id';  break;
		case 'ttl':  $ordering='s.title';  break;
	}

	 $ret['title']='MAIL PREDLOŠCI';
	

	$subject=trim(simGetParam($_REQUEST,'subject',''));
	$title=trim(simGetParam($_REQUEST,'title',''));
	
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	if ($title) { 
				$where[]="(s.title LIKE '%$title%')";
				$so[]='Naziv: '.stripslashes(trim($title)); 
		
	}
	if ($subject) { 
				$where[]="(s.subject LIKE '%$subject%')";
				$so[]='Subject: '.stripslashes(trim($subject)); 
		
	}
	

//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM mail as s ".$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['mailSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['mailINDEX']=$ret['index']; 
	$_SESSION['mailTITLE']=$ret['title']; 
	$_SESSION['mailSO']=$ret['searchoptions']; 
	
	
	return $ret;

}
class simMail extends simIzvodjacSugestionTable {
	var $id=null;
	var $subject=null;
	var $emfromname=null;
	var $title=null;
	var $emfrom=null;
	var $body=null;
	var $html=null;
	var $created=null;
	
	function simMail( &$db ) {
		$this->simDBTable( 'mail', 'id', $db );
	}
	

	
}
class simMailSent extends simIzvodjacSugestionTable {
	var $id=null;
	var $userID=null;
	var $senderID=null;
	var $email=null;
	var $mailID=null;
	var $sent_date=null;
	var $subject=null;
	var $body=null;
	var $failed=null;
	
	function simMailSent( &$db ) {
		$this->simDBTable( 'mail_sent', 'id', $db );
	}
	

	
}



?>