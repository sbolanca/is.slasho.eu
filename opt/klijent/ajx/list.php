<?



$index=trim(simGetParam($_SESSION,'klijentINDEX',''));

$SQL=trim($_SESSION['klijentSQL']);


$flds=explode(",",$_SESSION['tbl_klijent_fields']);


$q="SELECT s.* "
		."\n FROM klijent as s $index"
	
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
//if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $myID;
	$ret=array();
	$style=array();
	$class=array();
	if(intval($row['servis'])) $class[]="my";
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_klijent_fields'],'',$boja);

?>