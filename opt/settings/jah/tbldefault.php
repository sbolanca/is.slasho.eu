<?
$table=trim(simGetParam($_REQUEST,'table',''));

	$database->execQuery("DELETE FROM settings WHERE userID=$myID AND type IN ('".$table."_fields','".$table."_widths')");

	unset($_SESSION[$table.'_fields']);
	unset($_SESSION[$table.'_widths']);
	
	$res->javascript("window.location.reload( true );");

	
?>
