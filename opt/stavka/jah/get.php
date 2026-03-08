<?

$search=trim(simGetParam($_REQUEST,'search',''));
$val=trim(simGetParam($_REQUEST,'val',''));
$code=trim(simGetParam($_REQUEST,'code',''));
$fld=trim(simGetParam($_REQUEST,'fld',''));




if($search || $val) {
	if(intval($val)) $wh="id='".mysql_real_escape_string($val)."'";
	else if(trim($code)) $wh="code='".mysql_real_escape_string($code)."'";
	else  $wh="naziv='".mysql_real_escape_string($search)."'";

	$database->setQuery("SELECT id, naziv,code FROM stavka WHERE $wh");
	$rows=$database->loadObjectList();
	if (count($rows)) {
		$res->javascript("setAutoCompleteValues('stavka$fld','".$rows[0]->id."','".$rows[0]->naziv."','".$rows[0]->code."')");	
		if(count($rows)>1) {
			$res->alert('Pronađeno je više stavki u bazi.');
		}
	} else {
		$res->alert('Stavka nije pronađen.');
		$res->javascript("setAutoCompleteValues('stavka$fld','-','','')");
	}	
} else $res->javascript("setAutoCompleteValues('stavka$fld','-','','')");

?>