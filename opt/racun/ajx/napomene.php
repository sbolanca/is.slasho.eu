<?



$SQL=trim($_SESSION['racunnapomenaSQL']);


$flds=explode(",",$_SESSION['tbl_racunnapomena_fields']);


$q="SELECT s.*,r.cnt"
	." FROM racun_napomena as s"
	." LEFT JOIN (SELECT napomenaID,COUNT(id) as cnt FROM racun GROUP BY napomenaID) as r ON s.id=r.napomenaID"
	."\n $SQL "
	."\n LIMIT ".$posStart.",100";	

$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp);
$database->setQuery($q);

function boja($row) {
	$ret=array();
	$style=array();
	
	
	$row['tekst']=str_replace("\n"," ",$row['tekst']);
	
	
	$ret['row']=$row;
	return $ret;
}
$database->printRows($_SESSION['tbl_racunnapomena_fields'],'','boja');

?>