<?



$index=trim(simGetParam($_SESSION,'specijalniupitINDEX',''));

$SQL=trim($_SESSION['specijalniupitSQL']);


$flds=explode(",",$_SESSION['tbl_specijalniupit_fields']);


$q="SELECT s.* "
		."\n FROM specijalniupit as s $index"
	
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
	$row['opis']=str_replace("\n","   ",$row['opis']);
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_specijalniupit_fields'],'cnt',$boja);

?>