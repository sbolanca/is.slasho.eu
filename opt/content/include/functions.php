<?

function getFullContentRow($id,$fields="title,content",$sim=true) {
	global $database;
	$row=new simContent($database);
	$row->load($id);
	if ($sim) $row->convertVannaLinks($fields);
	return $row;
}




?>