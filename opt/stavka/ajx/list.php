<?

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$index=trim(simGetParam($_SESSION,'stavkaINDEX',''));

$SQL=trim($_SESSION['stavkaSQL']);
$FROM=trim($_SESSION['stavkaFROMSQL']);
$JOIN=trim($_SESSION['stavkaJOINSQL']);


$flds=explode(",",$_SESSION['tbl_stavka_fields']);


$q="SELECT s.*, k.naziv as kategorija,t.naziv as tip"
		.($pagetype=='folder'?",f.marker":"")
		."\n $FROM $index $JOIN"
		."\n LEFT JOIN kategorijastavke AS k ON k.id=s.kategorijaID "
		."\n LEFT JOIN tipstavke AS t ON t.id=s.tipID "
	
		."\n $SQL "
		."\n LIMIT ".$posStart.",100";	
		
if ($myID==1) {$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);


function boja($row) {
	global $myID,$pagetype;
	$ret=array();
	$style=array();
	$class=array();
	
	if($pagetype=='folder') {
		switch (intval($row['marker'])) {
			case 1: $class[]='blue'; break;
			case 2: $class[]='green'; break;
			case 3: $class[]='red'; break;
			case 4: $class[]='brown'; break;
			case 5: $class[]='ljub'; break;
		}
	}
	
	if(intval($row['materijal'])) $class[]="own";
	if(trim($row['iznos'])) $row['iznos']= makeHRFloat($row['iznos']);
	
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	$ret['row']=$row;
	return $ret;
}


$boja='boja';
$database->printRows($_SESSION['tbl_stavka_fields'],'',$boja);

?>