<?



$index=trim(simGetParam($_SESSION,'kategorijastavkeINDEX',''));

$SQL=trim($_SESSION['kategorijastavkeSQL']);


$flds=explode(",",$_SESSION['tbl_kategorijastavke_fields']);


$q="SELECT s.*, COUNT(o.id) as cnt"
		."\n FROM kategorijastavke as s $index"
		."\n LEFT JOIN stavka AS o ON s.id=o.kategorijaID "
	
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
$database->printRows($_SESSION['tbl_kategorijastavke_fields'],'',$boja);

?>