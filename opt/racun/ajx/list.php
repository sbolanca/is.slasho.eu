<?

include_once('opt/racun/include/functions.php');

$index=trim(simGetParam($_SESSION,'racunINDEX',''));

$SQL=trim($_SESSION['racunSQL']);


$flds=explode(",",$_SESSION['tbl_racun_fields']);


$osnovica="rs.kolicina*rs.cijena*(1-rs.popust/100)";

$q="SELECT s.*,n.naziv as napomena,"
." rs.osnovica, rs.pdv, rs.iznos,rs.cnt"
		."\n ,c.name as operater"
		."\n FROM racun as s"
		."\n LEFT JOIN user as c ON (c.id  =s.userID )"
		."\n LEFT JOIN 
			(SELECT rs.racunID, COUNT(rs.id) as cnt,SUM($osnovica) as osnovica,SUM(rs.stopa_pdv*$osnovica/100) as pdv,SUM((100+rs.stopa_pdv)*$osnovica/100) as iznos
			 FROM racun_stavka as rs GROUP BY rs.racunID) as rs ON (rs.racunID  =s.id )"
		."\n LEFT JOIN racun_napomena as n ON (s.napomenaID  =n.id )"
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
//if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $myID;
	$ret=array();
	$style=array();
	$class=array();
	if(intval($row['status'])) $class[]=getRacunStatusCls($row['status']);
	
	$row['modified']=convertSQLDateTimeToHr($row['modified']);
	$row['created']=convertSQLDateTimeToHr($row['created']);

	
	if (intval($row['marker'])) $class[]="red";
	else if (intval($row['klijentID'])) $class[]="blue";
	if (intval($row['recyclebin']))  $class[]='grey';
	
	$row['datum_izdavanja']=convertSQLDateTimeToHr($row['datum_izdavanja']);
	$row['datum_isporuke']=convertSQLDateTimeToHr($row['datum_isporuke']);
	$row['datum_dospijeca']=convertSQLDateTimeToHr($row['datum_dospijeca']);
	
	$row['iznos']=makeHRFloat($row['iznos'],'',true);
	$row['osnovica']=makeHRFloat($row['osnovica'],'',true);
	$row['pdv']=makeHRFloat($row['pdv'],'',true);
	
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_racun_fields'],'klijentID,status',$boja);

?>