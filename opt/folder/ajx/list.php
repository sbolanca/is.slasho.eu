<?

$folderID=intval(simGetParam($_REQUEST,'folderID',0));

$_SESSION['lastOpenMap']=$folderID;

$where=array();

 $where[]="i.folderID=$folderID";

		

$q="SELECT s.id,s.catid,s.marker,s.oznaceno,s.code,s.naziv,s.odlaganje"
		."\n FROM folder_arhiva as i"
		."\n LEFT JOIN hzp_arhiva as s ON s.id=i.arhivaID"
		."\n WHERE ".implode(" AND ",$where)
		."\n ORDER BY s.code"
		."\n LIMIT ".$posStart.",100";	

$database->setQuery($q);

function boja($row) {
	$ret=array();
	switch (intval($row['marker'])) {
		case 1: $ret['class']='mysukob'; break;
		case 2: $ret['class']='my'; break;
	}
	if (intval($row['oznaceno'])) $ret['style']="color:#dd0000";
	$ret['row']=$row;
	return $ret;
}




$database->printRows('code,naziv,odlaganje','catid','boja');
?>