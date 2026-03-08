<?
	$blank=array();
	$table=trim(simGetParam($_POST,'table',''));
	$fields=trim(implode(',',simGetParam($_POST,'fields',$blank)));
	if ($fields && !($fields==$_SESSION[$table."_fields"])) {
		$_SESSION[$table."_fields"]=$fields;
		$res->javascript($table."_fields='$fields';");
		$res->javascript("Init_$table()");
	} 
		
	$res->javascript("hideStandardPopup('editbox');");
	
	


	

?>
