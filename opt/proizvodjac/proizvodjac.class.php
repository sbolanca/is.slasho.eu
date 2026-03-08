<?

function trazProizvodjace($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','naziv'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_proizvodjac_fields'])) 
		$_SESSION['tbl_proizvodjac_fields']=$SETTINGS['tbl_proizvodjac_fields'];


	$ret['link']='opt=proizvodjac&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='PROIZVODĐAČI UREĐAJA';
	

	
	$proizvodjac=trim(simGetParam($_REQUEST,'proizvodjac',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($proizvodjac) { 
		$where[]="(s.naziv LIKE '$ex$proizvodjac$ex')"; 
		$so[]='naziv: '.stripslashes($proizvodjac).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM proizvodjac as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['proizvodjacSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['proizvodjacINDEX']=$ret['index']; 
	$_SESSION['proizvodjacTITLE']=$ret['title']; 
	$_SESSION['proizvodjacSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simProizvodjac extends simDBTable {
	var $id=null;
	var $naziv=null;
	
	
	
	function simProizvodjac( &$db ) {
		$this->simDBTable( 'proizvodjac', 'id', $db );
	}
	

	
}




?>