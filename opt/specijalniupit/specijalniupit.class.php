<?

function traziSpecijalneUpite($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','qopt'));
	
	$tip=simGetParam($_REQUEST,'selectItemtip',$blank);
	$ret['ord']=$ord;
	$ret['tip']=$tip;


	if (!isset($_SESSION['tbl_specijalniupit_fields'])) 
		$_SESSION['tbl_specijalniupit_fields']=$SETTINGS['tbl_specijalniupit_fields'];


	$ret['link']='opt=specijalniupit&act='.$pagetype;

	switch ($ord) {
		case 'pid':  $ordering='s.ordering,s.id'; break;
		case 'qopt':  $ordering='s.qopt,s.naziv'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='SPECIJALNI UPITI';
	

	
	$specijalniupit=trim(simGetParam($_REQUEST,'specijalniupit',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($specijalniupit) { 
		$where[]="(s.naziv LIKE '$ex$specijalniupit$ex')"; 
		$so[]='naziv: *'.stripslashes($specijalniupit).'* '; 
	}
	
	if (count($tip)) {
		
		$svrste=implode(", ",$tip);
		$where[]="(s.qopt IN ('".implode("','",$tip)."'))";
		$so[]='tip: '.$svrste; 
	}
	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM specijalniupit as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['specijalniupitSQL']=$ret['whereSQL']."  ".$ret['orderingSQL']; 
	$_SESSION['specijalniupitINDEX']=$ret['index']; 
	$_SESSION['specijalniupitTITLE']=$ret['title']; 
	$_SESSION['specijalniupitSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simSpecijalniUpit extends simIzvodjacSugestionTable {
	var $id=null;
	var $qopt=null;
	var $naziv=null;
	var $opis=null;
	var $upit=null;
	var $leftjoin=null;
	var $uvjet=null;
	var $veza=null;
	var $ordering=null;
	
	function simSpecijalniUpit( &$db ) {
		$this->simDBTable( 'specijalniupit', 'id', $db );
	}
	

	
}




?>