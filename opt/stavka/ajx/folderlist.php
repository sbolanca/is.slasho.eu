<?

$folderID=intval(simGetParam($_REQUEST,'folderID',0));

$flds=explode(",",$_SESSION['tblF_stavka_fields']);

$where=array();

 $where[]="i.folderID=$folderID";
 //$where[]="s.recyclebin=0";

		

$q="SELECT s.*,i.marker,k.naziv as kategorija,t.naziv as tip"
		."\n FROM sta_folder_stavka as i"
		."\n LEFT JOIN stavka as s ON s.id=i.stavkaID"
		."\n LEFT JOIN tipstavke as t ON t.id=s.tipID"
		."\n LEFT JOIN kategorijastavke as k ON k.id=s.kategorijaID"
		."\n WHERE ".implode(" AND ",$where)
		."\n ORDER BY k.id,s.id DESC"
		."\n LIMIT ".$posStart.",$count";	

$database->setQuery($q);

function boja($row) {
	global $myID;
	$ret=array();
	$style=array();
	$class=array();
	$color='';
	
	switch (intval($row['marker'])) {
			case 1: $class[]='blue'; break;
			case 2: $class[]='green'; break;
			case 3: $class[]='red'; break;
			case 4: $class[]='brown'; break;
			case 5: $class[]='ljub'; break;
		}
	
	
	if(intval($row['materijal'])) $class[]="own";
	
	
	if (count($style)) $ret['style']=implode(";",$style);
	if (count($class)) $ret['class']=implode(" ",$class);
	
	$ret['row']=$row;
	return $ret;
}

$boja='boja'; 
$database->printRows($_SESSION['tblF_stavka_fields'],'',$boja);
?>