<?

function trazKatSlike($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','ord'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_kategorijaslike_fields'])) 
		$_SESSION['tbl_kategorijaslike_fields']=$SETTINGS['tbl_kategorijaslike_fields'];


	$ret['link']='opt=kategorijaslike&act='.$pagetype;

	switch ($ord) {
		case 'ord':  $ordering='s.ordering'; break;
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='KATEGORIJE (GRUPE) SLIKA';
	

	
	$sifra=trim(simGetParam($_REQUEST,'sifra',''));
	$kategorijaslike=trim(simGetParam($_REQUEST,'kategorijaslike',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	if ($sifra) { 
		$where[]="(s.id='$sifra')"; 
		$so[]='ID: '.stripslashes($sifra).' '; 
	}
	if ($kategorijaslike) { 
		$where[]="(s.naziv LIKE '$ex$kategorijaslike$ex')"; 
		$so[]='naziv: '.stripslashes($kategorijaslike).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM kategorijaslike as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['kategorijaslikeSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['kategorijaslikeINDEX']=$ret['index']; 
	$_SESSION['kategorijaslikeTITLE']=$ret['title']; 
	$_SESSION['kategorijaslikeSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simKategorijaSlike extends simDBTable {
	var $id=null;
	var $naziv=null;
	var $ordering=null;
	
	
	
	function simKategorijaSlike( &$db ) {
		$this->simDBTable( 'kategorijaslike', 'id', $db );
	}
	

	
}




?>