<?

$id=intval(simGetParam($_REQUEST,'id','0'));
$where[]='i.parentID='.$id 
.(!intval($id) ?
"\n  AND (i.userID=$myID "
."\n OR (i.sharing=4 AND FIND_IN_SET('$myID',i.visibility)>0)"
."\n OR (i.sharing=5)"
."\n )" : "");	

$q="SELECT i.id,u.name,u.super,i.userID,i.sharing, i.visibility,i.naziv,i.parentID, COUNT(DISTINCT(s.id)) as subs, COUNT(DISTINCT(s.id)) + COUNT(DISTINCT(m.arhivaID)) as content,COUNT(DISTINCT(m.arhivaID)) as cnt"
		."\n FROM folder as i"
		."\n LEFT JOIN user as u ON u.id=i.userID"
		."\n LEFT JOIN folder as s ON s.parentID=i.id"
		."\n LEFT JOIN folder_arhiva as m ON (m.folderID=i.id)"
		.(count($where) ? "\n WHERE ".implode(" AND ",$where) : '')
		."\n GROUP BY i.id"
		."\n ORDER BY i.naziv ASC";

//$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp);
$database->setQuery($q);

function boja($row) {
	global $myID;
	$r=$row;
	$ret=array();
	if (intval($row['content'])) $ret['im0']='folderClosed.gif';
	switch(intval($row['sharing'])) {
		case 0:  break;
		case 5: $ret['aCol']="#0000bb"; $ret['sCol']="#bbbbff"; break;
		default: $ret['aCol']="#008800"; $ret['sCol']="#bbffbb";
		
	}
	if (!(intval($row['userID'])==$myID)) {
			$ret['aCol']="#bb0000";
			$ret['sCol']="#ffbbbb";
			if (intval($row['sharing'])) {
				$r['naziv'].=" [".$r['name']."]";
			}
	}
	$ret['row']=$r;
	return $ret;
}

$database->printSimpleTree($id,'naziv','subs','parentID,userID,sharing,cnt','boja');

?>