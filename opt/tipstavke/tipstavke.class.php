<?

function trazTipStavke($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','naziv'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_tipstavke_fields'])) 
		$_SESSION['tbl_tipstavke_fields']=$SETTINGS['tbl_tipstavke_fields'];


	$ret['link']='opt=tipstavke&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='TIPOVI STAVKE';
	

	
	$sifra=trim(simGetParam($_REQUEST,'sifra',''));
	$tipstavke=trim(simGetParam($_REQUEST,'tipstavke',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	if ($sifra) { 
		$where[]="(s.id='$sifra')"; 
		$so[]='ID: '.stripslashes($sifra).' '; 
	}
	if ($tipstavke) { 
		$where[]="(s.naziv LIKE '$ex$tipstavke$ex')"; 
		$so[]='naziv: '.stripslashes($tipstavke).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM tipstavke as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['tipstavkeSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['tipstavkeINDEX']=$ret['index']; 
	$_SESSION['tipstavkeTITLE']=$ret['title']; 
	$_SESSION['tipstavkeSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simTipStavke extends simDBTable {
	var $id=null;
	var $naziv=null;
	
	
	
	function simTipStavke( &$db ) {
		$this->simDBTable( 'tipstavke', 'id', $db );
	}
	

	
}




?>