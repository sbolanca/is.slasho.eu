<?


$index=trim(simGetParam($_SESSION,'INDEX',''));

$SQL=trim($_SESSION['SQL']);


$flds=explode(",",$_SESSION['tbl_log_fields']);


$q="SELECT s.*,DATE_FORMAT(s.created,'%d.%m.%Y %H:%i') as fcreated "
		."\n FROM log as s $index"
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
//if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);

function boja($row) {
	$ret=array();
	
	if ($row['opt']=='login') {
		$ret['class']='my';
		if ($row['act']=='failed') $ret['class']='sukob';
		else if ($row['act']=='logout') $ret['class']='own';
	}
	$ret['row']=$row;
	return $ret;
}

$database->printRows($_SESSION['tbl_log_fields'],'parentID','boja');

?>