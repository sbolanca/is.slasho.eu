<?
include_once('opt/ponuda/include/functions.php');


function traziPonude($pagetype) {
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

	

	if (!isset($_SESSION['tbl_ponuda_fields'])) 
		$_SESSION['tbl_ponuda_fields']=$SETTINGS['tbl_ponuda_fields'];


	$ret['link']='opt=ponuda&act='.$pagetype;

	switch ($ord) {
		case 'id':  $ordering='s.id DESC'; break;
		case 'sif':  $ordering='s.code*1 DESC';  break;
		case 'naziv':  $ordering='s.naziv,s.id DESC';  break;
		case 'izn':  $ordering='s.iznos';  break;
		case 'dat':  $ordering='s.datum_izdavanja DESC, s.id DESC';  break;
		case 'dsp':  $ordering='s.datum_dospijeca DESC, s.id DESC';  break;
	}

	$ret['title']='PONUDE';
	
	$status=intval(simGetParam($_REQUEST,'status',-1));
	$ponuda=trim(simGetParam($_REQUEST,'ponuda',''));
	$naziv=trim(simGetParam($_REQUEST,'naziv',''));
	$oib=trim(simGetParam($_REQUEST,'oib',''));
	
	$exact=intval(simGetParam($_REQUEST,'exact',0));
	$ex=($exact ? '' : '%'); 
	
	
	
	
	if ($ponuda) { 
		$where[]="(s.id='$ponuda' OR s.code LIKE '$ponuda')"; 
		$so[]='ponuda: '.stripslashes($ponuda).' '; 
	}
	if ($status>-1) { 
		$where[]="(s.status='$status')"; 
		$so[]='status: '.getPonudaStatusTitle($status).' '; 
	}
	if ($naziv) { 
		$where[]="(s.naziv LIKE '$naziv%' OR s.naziv LIKE '% $naziv')"; 
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


	$database->setQuery("SELECT COUNT(DISTINCT(s.id)) FROM ponuda as s "
	.$ret['index']." ".$ret['whereSQL']);
	//echo $database->_sql;
	$ret['cnt']=intval($database->loadResult());
	
	$_SESSION['total_count']=$ret['cnt'];
	$_SESSION['ponudaSQL']=$ret['whereSQL']." GROUP BY s.id ".$ret['orderingSQL']; 
	$_SESSION['ponudaINDEX']=$ret['index']; 
	$_SESSION['ponudaTITLE']=$ret['title']; 
	$_SESSION['ponudaSO']=$ret['searchoptions']; 
	
	
	return $ret;

}

class simPonuda extends simDBTable {
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
	var $datum_dospijeca=null;
	var $recyclebin=null;
	var $status=null;
	var $marker=null;
	var $opis=null;
	var $created=null;
	var $createdByID=null;
	var $modified=null;
	var $modifiedByID=null;
	var $napomenaID=null;
	
	function simPonuda( &$db ) {
		$this->simDBTable( 'ponuda', 'id', $db );
	}
	function calculateIznos($sufix='',$printZero=false) {
		$this->addField('osnovica,iznos,pdv,cnt');
		$osnovica="kolicina*cijena*(1-popust/100)";
		$this->_db->setQuery("SELECT COUNT(id) as cnt,SUM($osnovica) as osnovica,"
		." SUM(stopa_pdv*$osnovica/100) as pdv,"
		." SUM((100+stopa_pdv)*$osnovica/100) as iznos "
		." FROM ponuda_stavka WHERE ponudaID=".$this->id);
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
		$res->javascript("setRacunIznosi('".$this->osnovica."','".$this->pdv."','".$this->iznos."','ponudaForm');");
	}
	function convertAllDatesToHR() {
		$this->convertSQLDateToHR('modified',true);
		$this->convertSQLDateToHR('created',true);
		$this->convertSQLDateToHR('datum_izdavanja');
		$this->convertSQLDateToHR('datum_dospijeca');
	}
	
}

class simPonudaStavka extends simDBTable {
	var $id=null;
	var $ponudaID=null;
	var $stavkaID=null;
	var $ordering=null;
	var $opis=null;
	var $mjera=null;
	var $kolicina=null;
	var $stopa_pdv=null;
	var $cijena=null;
	var $popust=null;
	
	
	
	function simPonudaStavka( &$db ) {
		$this->simDBTable( 'ponuda_stavka', 'id', $db );
	}
	
}


?>