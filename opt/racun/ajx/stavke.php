<?

$id=intval(simGetParam($_REQUEST,'id',0));

$osnovica="rs.kolicina*rs.cijena*(1-rs.popust/100)";
$q="SELECT rs.*, "
	." $osnovica as osnovica,rs.stopa_pdv*$osnovica/100 as pdv,(100+rs.stopa_pdv)*$osnovica/100 as iznos"
	." FROM racun_stavka as rs WHERE rs.racunID=$id"
	." ORDER BY rs.ordering";

$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp);
$database->setQuery($q);

function boja($row) {
	$ret=array();
	$style=array();
	
	
	$row['opis']=str_replace("\n"," ",$row['opis']);
	
	$row['cijena']=makeHRFloat($row['cijena'],'',true);
	$row['iznos']=makeHRFloat($row['iznos'],'',true);
	$row['osnovica']=makeHRFloat($row['osnovica'],'',true);
	$row['pdv']=makeHRFloat($row['pdv'],'',true);
	if (count($style)) $ret['style']=implode(";",$style);
	$ret['row']=$row;
	return $ret;
}
$database->printRows($_SESSION['tbl_stavke_fields'],'racunID,stavkaID','boja');

?>