<?

function trazKatStavke($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','naziv'));
	
	$ret['ord']=$ord;


	if (!isset($_SESSION['tbl_kategorijastavke_fields'])) 
		$_SESSION['tbl_kategorijastavke_fields']=$SETTINGS['tbl_kategorijastavke_fields'];


	$ret['link']='opt=kategorijastavke&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	}

	$ret['title']='KATEGORIJE (GRUPE) STAVKI';
	

	
	$sifra=trim(simGetParam($_REQUEST,'sifra',''));
	$kategorijastavke=trim(simGetParam($_REQUEST,'kategorijastavke',''));
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	if ($sifra) { 
		$where[]="(s.id='$sifra')"; 
		$so[]='ID: '.stripslashes($sifra).' '; 
	}
	if ($kategorijastavke) { 
		$where[]="(s.naziv LIKE '$ex$kategorijastavke$ex')"; 
		$so[]='naziv: '.stripslashes($kategorijastavke).' '; 
	}
	
	

	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM kategorijastavke as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['kategorijastavkeSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['kategorijastavkeINDEX']=$ret['index']; 
	$_SESSION['kategorijastavkeTITLE']=$ret['title']; 
	$_SESSION['kategorijastavkeSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simKategorijaStavke extends simDBTable {
	var $id=null;
	var $naziv=null;
	
	
	
	function simKategorijaStavke( &$db ) {
		$this->simDBTable( 'kategorijastavke', 'id', $db );
	}
	

	
}




?>