<?



$index=trim(simGetParam($_SESSION,'userINDEX',''));

$SQL=trim($_SESSION['userSQL']);

$userOrderingSQL=trim($_SESSION['userOrderingSQL']);

$flds=explode(",",$_SESSION['tbl_user_fields']);


$q="SELECT s.*,"
		."\n GROUP_CONCAT(CONCAT(p.opt,'[',p.permission,']')) as permissions"
		."\n FROM user as s $index"
		."\n LEFT JOIN permissions as p ON s.username=p.username"
		."\n $SQL "
		."\n GROUP BY s.id"
		."\n $userOrderingSQL"
		."\n LIMIT ".$posStart.",100";	
		
//{$fp=fopen("sql.txt","a");fputs($fp,";\n".$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $simConfig_absolute_path,$simConfig_db,$isSuper,$myID;
	
	
	
	
	$ret=array();
	$style=array();
	$class=array();
	$color='';
	$sukob=false;
	
	
	
	if (intval(!$row['active'])) $class[]='grey';
	
		
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_user_fields'],'active',$boja);

?>