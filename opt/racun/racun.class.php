<?
include_once('opt/racun/include/functions.php');


function traziRacune($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','id'));
	
	$users=simGetParam($_REQUEST,'selectItemuser',$blank);
	$klijents=simGetParam($_REQUEST,'selectItemklijent',$blank);
	$ret['ord']=$ord;

	$obrgod=getObracunskaGodina();
	$ret['obrgod']=$obrgod;
	$where[]="YEAR(s.datum_izdavanja)=$obrgod";

	if (!isset($_SESSION['tbl_racun_fields'])) 
		$_SESSION['tbl_racun_fields']=$SETTINGS['tbl_racun_fields'];


	$ret['link']='opt=racun&act='.$pagetype;

	switch ($ord) {
		case 'id':  $ordering='s.id DESC'; break;
		case 'sif':  $ordering='s.code*1 DESC';  break;
		case 'naziv':  $ordering='s.naziv,s.id DESC';  break;
		case 'izn':  $ordering='s.iznos';  break;
		case 'dat':  $ordering='s.datum_izdavanja DESC, s.id DESC';  break;
		case 'dsp':  $ordering='s.datum_dospijeca DESC, s.id DESC';  break;
	}

	$ret['title']='RAČUNI';
	
	$sif=trim(simGetParam($_REQUEST,'sif',''));
	$status=intval(simGetParam($_REQUEST,'status',-1));
	$racun=trim(simGetParam($_REQUEST,'racun',''));
	$naziv=trim(simGetParam($_REQUEST,'naziv',''));
	$oib=trim(simGetParam($_REQUEST,'oib',''));
	
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	if ($sif) { 
				$where[]="(s.id IN ('".implode("','",explode(",",$sif))."'))";
				$so[]='ID: '.substr(trim($sif),0,50).(strlen($sif)>50?'...':''); 
	}
	if ($racun) { 
		$where[]="(s.id='$racun' OR s.code LIKE '$racun')"; 
		$so[]='racun: '.stripslashes($racun).' '; 
	}
	if ($status>-1) { 
		$where[]="(s.status='$status')"; 
		$so[]='status: '.getRacunStatusTitle($status).' '; 
	}
	if ($naziv) { 
		$where[]="(s.naziv LIKE '$naziv%' OR s.naziv LIKE '% $naziv%')"; 
		$so[]='naziv: '.stripslashes($naziv).' '; 
	}
	if ($oib) { 
		$where[]="(s.oib ='$oib')"; 
		$so[]='oib: '.stripslashes($oib).' '; 
	}
	
	
	if (count($klijents)) {
		
		$llist=implode(", ",$klijents);
		$where[]="(s.klijentID IN ($llist))";
		$so[]='klijent: '.$database->loadResultArrayText("SELECT naziv FROM klijent WHERE id IN ($llist)"); 
	}
	
	if (count($users)) {
		
		$ulist=implode(", ",$users);
		$where[]="(s.userID IN ($ulist))";
		$so[]='djelatnik: '.$database->loadResultArrayText("SELECT name FROM user WHERE id IN ($ulist)",", "); 
	}
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;

	$osnovica="rs.kolicina*rs.cijena*(1-rs.popust/100)";
	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) as cnt,SUM(rs.iznos) as suma FROM racun as s "
	."\n LEFT JOIN 
			(SELECT rs.racunID, COUNT(rs.id) as cnt,SUM($osnovica) as osnovica,SUM(rs.stopa_pdv*$osnovica/100) as pdv,SUM((100+rs.stopa_pdv)*$osnovica/100) as iznos
			 FROM racun_stavka as rs GROUP BY rs.racunID) as rs ON (rs.racunID  =s.id )"
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$database->loadObject($cntsum);
	$ret['cnt']=intval($cntsum->cnt);
	$ret['sum']=makeHRFloat($cntsum->suma);
	
	
	
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['racunSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['racunINDEX']=$ret['index']; 
	$_SESSION['racunTITLE']=$ret['title']; 
	$_SESSION['racunSO']=$ret['searchoptions']; 
	
	
	return $ret;

}



function traziNapomeneRacuna($pagetype) {
	global $database,$myID,$SETTINGS;
	
	$ret=array();
	$blank=array();
	$so=array();
	
	$where=array();
	$ret['index']="";
	$ret['searchoptions']='';
		
	$ord=trim(simGetParam($_REQUEST,'ord','id'));
	
	$ret['ord']=$ord;

	

	if (!isset($_SESSION['tbl_racunnapomena_fields'])) 
		$_SESSION['tbl_racunnapomena_fields']=$SETTINGS['tbl_racunnapomena_fields'];


	$ret['link']='opt=racun&act='.$pagetype;

	switch ($ord) {
		case 'id':  $ordering='s.id'; break;
		case 'naziv':  $ordering='s.naziv';  break;
	
	}

	$ret['title']='NAPOMENE RAČUNA';
	
	$sif=trim(simGetParam($_REQUEST,'sif',''));
	$tekst=trim(simGetParam($_REQUEST,'tekst',''));
	$naziv=trim(simGetParam($_REQUEST,'naziv',''));
	
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	
	if ($sif) { 
		$where[]="(s.id IN ($sif))"; 
		$so[]='ID: '.stripslashes($sif).' '; 
	}
	
	if ($naziv) { 
		$where[]="(s.naziv LIKE '$naziv%' OR s.naziv LIKE '% $naziv')"; 
		$so[]='naziv: '.stripslashes($naziv).' '; 
	}
	if ($tekst) { 
		$where[]="(s.tekst LIKE '$tekst%' OR s.tekst LIKE '% $tekst')"; 
		$so[]='tekst: '.stripslashes($tekst).' '; 
	}
	
	
	
//$where[]="(s.recyclebin=".(!($pagetype=='recyclebin') ? 0 : 1).")";

	$ret['searchoptions']=implode(", ",$so);
	

	$ret['whereSQL']=(count($where) ? "\n WHERE ".implode(" AND ",$where) : '');
			

	$ret['orderingSQL']=" ORDER BY ".$ordering;


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM racun_napomena as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['racunnapomenaSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['racunnapomenaTITLE']=$ret['title']; 
	$_SESSION['racunnapomenaSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simRacun extends simDBTable {
	var $id=null;
	var $klijentID=null;
	var $podruznicaID=null;
	var $userID=null;
	var $code=null;
	var $naziv=null;
	var $oib=null;
	var $adresa=null;
	var $sjediste=null;
	var $datum_izdavanja=null;
	var $datum_isporuke=null;
	var $vrijeme_izdavanja=null;
	var $datum_dospijeca=null;
	var $nacin_placanja=null;
	var $recyclebin=null;
	var $status=null;
	var $marker=null;
	var $opis=null;
	var $created=null;
	var $createdByID=null;
	var $modified=null;
	var $modifiedByID=null;
	var $napomenaID=null;
	
	function simRacun( &$db ) {
		$this->simDBTable( 'racun', 'id', $db );
	}
	
	function calculateIznos($sufix='',$printZero=false) {
		$this->addField('osnovica,iznos,pdv,cnt');
		$osnovica="kolicina*cijena*(1-popust/100)";
		$this->_db->setQuery("SELECT COUNT(id) as cnt,SUM($osnovica) as osnovica,"
		." SUM(stopa_pdv*$osnovica/100) as pdv,"
		." SUM((100+stopa_pdv)*$osnovica/100) as iznos "
		." FROM racun_stavka WHERE racunID=".$this->id);
		$this->_db->loadObject($this);
		$this->osnovica=makeHRFloat($this->osnovica,$sufix,$printZero);
		$this->pdv=makeHRFloat($this->pdv,$sufix,$printZero);
		$this->iznos=makeHRFloat($this->iznos,$sufix,$printZero);
	}
	function removeSufix($sufix=' kn') {
		if(isset($this->osnovica)) $this->osnovica=str_replace($sufix,'',$this->osnovica);
		if(isset($this->pdv)) $this->pdv=str_replace($sufix,'',$this->pdv);
		if(isset($this->iznos)) $this->iznos=str_replace($sufix,'',$this->iznos);
	}
	function setJavascript(&$res) {
		$res->javascript("setRacunIznosi('".$this->osnovica."','".$this->pdv."','".$this->iznos."');");
	}
	function convertAllDatesToHR() {
		$this->convertSQLDateToHR('modified',true);
		$this->convertSQLDateToHR('created',true);
		$this->convertSQLDateToHR('datum_izdavanja');
		$this->convertSQLDateToHR('datum_isporuke');
		$this->convertSQLDateToHR('datum_dospijeca');
	}
}
class simRacunStavka extends simDBTable {
	var $id=null;
	var $racunID=null;
	var $stavkaID=null;
	var $ordering=null;
	var $opis=null;
	var $mjera=null;
	var $kolicina=null;
	var $stopa_pdv=null;
	var $cijena=null;
	var $popust=null;
	
	
	
	function simRacunStavka( &$db ) {
		$this->simDBTable( 'racun_stavka', 'id', $db );
	}
	
}
class simRacunNapomena extends simDBTable {
	var $id=null;
	var $naziv=null;
	var $tekst=null;
	
	
	
	function simRacunNapomena( &$db ) {
		$this->simDBTable( 'racun_napomena', 'id', $db );
	}
	
}


?>