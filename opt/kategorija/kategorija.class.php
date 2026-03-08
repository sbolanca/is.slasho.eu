<?

function traziKategorije($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','naziv'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_kategorija_fields'])) 
		$_SESSION['tbl_kategorija_fields']=$SETTINGS['tbl_kategorija_fields'];


	$ret['link']='opt=kategorija&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='KATEGORIJE UREĐAJA';
	

	
	$kategorija=trim(simGetParam($_REQUEST,'kategorija',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($kategorija) { 
		$where[]="(s.naziv LIKE '$ex$kategorija$ex')"; 
		$so[]='naziv: '.stripslashes($kategorija).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM kategorija as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['kategorijaSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['kategorijaINDEX']=$ret['index']; 
	$_SESSION['kategorijaTITLE']=$ret['title']; 
	$_SESSION['kategorijaSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simKategorija extends simDBTable {
	var $id=null;
	var $naziv=null;
	
	
	
	function simKategorija( &$db ) {
		$this->simDBTable( 'kategorija', 'id', $db );
	}
	

	
}




?>