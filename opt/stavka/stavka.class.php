<?

function traziStavke($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
	
	$ret['fromSQL']="FROM stavka as s "; 
	$ret['joinSQL']=""; 
	$ret['groupSQL']="GROUP BY s.id"; 
	
	$ord=trim(simGetParam($_REQUEST,'ord','kat'));
	
	$kats=simGetParam($_REQUEST,'selectItemkat',$blank);
	$tips=simGetParam($_REQUEST,'selectItemtip',$blank);
	$ret['ord']=$ord;
	
	$folderID=intval(simGetParam($_REQUEST,'folderID',''));
	$ret['folderID']=$folderID;

	if (!isset($_SESSION['tbl_stavka_fields'])) 
		$_SESSION['tbl_stavka_fields']=$SETTINGS['tbl_stavka_fields'];


	$ret['link']='opt=stavka&act='.$pagetype;

	switch ($ord) {
		case 'sif':  $ordering='s.id'; break;
		case 'code':  $ordering='s.code'; break;
		case 'naziv':  $ordering='s.naziv';  break;
		case 'kat':  $ordering='k.naziv,s.id';  break;
		case 'tip':  $ordering='t.naziv,k.naziv,s.id';  break;
		case 'mje':  $ordering='s.mjera,k.naziv,s.naziv';  break;
		case 'izna':  $ordering='s.iznos ASC';  break;
		case 'iznd':  $ordering='s.iznos DESC';  break;
	}
	
	switch($pagetype) {
		case 'folder' : 
			include("opt/stavka/folder.class.php");
			
			$ret['link']="folderID=$folderID";
		
			$ret['fromSQL']="FROM sta_folder_stavka AS f LEFT JOIN stavka as s ON s.id=f.stavkaID"; 
			$fld=new simStaFolder($database);
			$fld->loadFull($folderID);
			$where[]="f.folderID=$folderID"; 
			$ffilter=intval(simGetParam($_REQUEST,'ffilter',-1));
			if($ffilter>-1) {
				$so[]=$fld->getBojaName($ffilter);
				$where[]="f.marker=$ffilter"; 
			}
			
			$ret['title']='FOLDER STAVKI: '.$fld->naziv; 
			$so[]='vlasnik: '.$fld->name;
			if (intval($fld->parentID)) {
				$path=array_reverse(loadPathId('folder',$fld->parentID));
				$parentpaths=$database->loadResultArrayText("SELECT naziv FROM sta_folder WHERE id IN (".implode(',',$path).")",' -> ');
				$so[]=$parentpaths;
			}
			$ret['folder']=$fld;
			
			break;
			default: $ret['title']='STAVKE';
	}
	
	
	

	
	$sifra=trim(simGetParam($_REQUEST,'sifra',''));
	$stavka=trim(simGetParam($_REQUEST,'stavka',''));
	$mjera=trim(simGetParam($_REQUEST,'mjera',''));
	$materijal=intval(simGetParam($_REQUEST,'materijal',0));
	$code=trim(simGetParam($_REQUEST,'code',''));
	
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($sifra) { 
		$where[]="(s.id='$sifra')"; 
		$so[]='sifra: '.stripslashes($sifra).' '; 
	}
	if ($materijal) { 
		$where[]="(s.materijal='$materijal')"; 
		$so[]='materijal'; 
	}
	if ($code) { 
		$where[]="(s.code='$code')"; 
		$so[]='code: '.stripslashes($code).' '; 
	}
	if ($stavka) { 
		$where[]="s.naziv LIKE '$ex$stavka$ex'"; 
		$so[]='naziv: '.stripslashes($stavka).' '; 
	}
	if ($mjera) { 
		$where[]="(s.mjera='$mjera')"; 
		$so[]='mjera: '.stripslashes($mjera).' '; 
	}
	
	
	if (count($kats)) {
		
		$klist=implode(", ",$kats);
		$where[]="(s.kategorijaID IN ($klist))";
		$so[]='kategorija: '.$database->loadResultArrayText("SELECT naziv FROM kategorijastavke WHERE id IN ($klist)"); 
	}
	if (count($tips)) {
		
		$tlist=implode(", ",$tips);
		$where[]="(s.tipID IN ($tlist))";
		$so[]='tip: '.$database->loadResultArrayText("SELECT naziv FROM tipstavke WHERE id IN ($tlist)"); 
	}
	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) ".$ret['fromSQL']." ".$ret['index']." ".$ret['joinSQL']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['stavkaSQL']=$ret['whereSQL']." ".$ret['groupSQL']." ".$ret['orderingSQL']; 
	$_SESSION['stavkaINDEX']=$ret['index']; 
	$_SESSION['stavkaFROMSQL']=$ret['fromSQL'];
	$_SESSION['stavkaJOINSQL']=$ret['joinSQL'];
	$_SESSION['stavkaRawSQL']=$ret['fromSQL']." ".$ret['index']." ".$ret['joinSQL']." ".$ret['whereSQL']." ".$ret['groupSQL']; 
	$_SESSION['stavkaTITLE']=$ret['title']; 
	$_SESSION['stavkaSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simStavka extends simDBTable {
	var $id=null;
	var $kategorijaID=null;
	var $tipID=null;
	var $naziv=null;
	var $iznos=null;
	var $stopa_pdv=null;
	var $mjera=null;
	var $code=null;
	var $materijal=null;
	
	
	function simStavka( &$db ) {
		$this->simDBTable( 'stavka', 'id', $db );
	}
	

	
}




?>