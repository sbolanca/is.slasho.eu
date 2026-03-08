<?

$id=intval(simGetParam($_REQUEST,'id','0'));
$where[]='i.parentID='.$id 
.(!intval($id) ?
"\n  AND (i.userID=$myID "
.($isSuper ? "\n OR (i.sharing=2)"  : '')
."\n OR (i.sharing=4 AND FIND_IN_SET('$myID',i.visibility)>0)"
."\n OR (i.sharing=5)"
."\n )" : "");	
$where[]="((FIND_IN_SET('$myID',i.hide) IS NULL) OR (FIND_IN_SET('$myID',i.hide)=0))";

$q="SELECT i.id,u.name,i.userID,i.sharing,i.visibility,i.naziv,i.parentID, COUNT(DISTINCT(s.id)) as subs, COUNT(DISTINCT(s.id)) + COUNT(DISTINCT(m.stavkaID)) as content,COUNT(DISTINCT(m.stavkaID)) as cnt"
		."\n FROM sta_folder as i"
		."\n LEFT JOIN user as u ON u.id=i.userID"
		."\n LEFT JOIN sta_folder as s ON s.parentID=i.id"
		."\n LEFT JOIN sta_folder_stavka as m ON (m.folderID=i.id)"
		.(count($where) ? "\n WHERE ".implode(" AND ",$where) : '')
		."\n GROUP BY i.id"
		."\n ORDER BY i.naziv ASC";


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
				//$super=(intval($r['super'])-1) ? 'POSLuH' : 'HUZIP';	
				//if (intval($r['sharing'])>1) 
				$r['naziv'].=" &nbsp;&nbsp; [".$r['name']."]";
			}
	}
	$ret['row']=$r;
	return $ret;
}

$database->printSimpleTree($id,'naziv','subs','parentID,userID,sharing,cnt','boja');

?>