<?

function trazKlijente($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','naziv'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_klijent_fields'])) 
		$_SESSION['tbl_klijent_fields']=$SETTINGS['tbl_klijent_fields'];


	$ret['link']='opt=klijent&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='KLIJENTI';
	

	
	$klijent=trim(simGetParam($_REQUEST,'klijent',''));
	$srediste=trim(simGetParam($_REQUEST,'srediste',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($klijent) { 
		$where[]="(s.naziv LIKE '$ex$klijent$ex')"; 
		$so[]='naziv: '.stripslashes($klijent).' '; 
	}
	if ($srediste) { 
		$where[]="(s.srediste LIKE '$ex$srediste$ex')"; 
		$so[]='naziv: '.stripslashes($srediste).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM klijent as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['klijentSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['klijentINDEX']=$ret['index']; 
	$_SESSION['klijentTITLE']=$ret['title']; 
	$_SESSION['klijentSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simKlijent extends simDBTable {
	var $id=null;
	var $naziv=null;
	var $oib=null;
	var $puni_naziv=null;
	var $adresa=null;
	var $sjediste=null;
	var $telefon=null;
	var $email=null;
	var $servis=null;
	
	
	
	function simKlijent( &$db ) {
		$this->simDBTable( 'klijent', 'id', $db );
	}
	

	
}




?>