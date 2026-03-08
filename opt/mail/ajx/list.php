<?



$index=trim(simGetParam($_SESSION,'mailINDEX',''));

$SQL=trim($_SESSION['mailSQL']);


$flds=explode(",",$_SESSION['tbl_mail_fields']);


$q="SELECT s.*, COUNT(m.id) as cnt,"
		."\n IF(DATE(s.created)=DATE(NOW()),DATE_FORMAT(s.created,'%H:%i'),DATE_FORMAT(s.created,'%d.%m.%Y')) as fcreated"
		."\n FROM mail as s $index"
		."\n LEFT JOIN mail_sent as m ON m.mailID=s.id"
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
//if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $myID;
	$ret=array();
	$style=array();
	$color='';
	$sukob=false;
	
	$body=strip_tags($row['body']);
	$row['body']=str_replace("\r"," ",str_replace("\n"," ",(strlen($body)>200)?substr($body,0,200)."...":$body));
	
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_mail_fields'],'',$boja);

?>