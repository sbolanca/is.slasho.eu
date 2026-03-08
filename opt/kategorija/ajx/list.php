<?



$index=trim(simGetParam($_SESSION,'kategorijaINDEX',''));

$SQL=trim($_SESSION['kategorijaSQL']);


$flds=explode(",",$_SESSION['tbl_kategorija_fields']);


$q="SELECT s.*, COUNT(DISTINCT(m.id)) as cnt,COUNT(u.id) as cntu "
		."\n FROM kategorija as s $index"
		."\n LEFT JOIN model AS m ON s.id=m.kategorijaID "
		."\n LEFT JOIN uredjaj AS u ON m.id=u.modelID "
	
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
//if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $myID;
	$ret=array();
	$style=array();
	$class=array();
	//if(!intval($row['cnt'])) $class[]="sukob";
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_kategorija_fields'],'',$boja);

?>