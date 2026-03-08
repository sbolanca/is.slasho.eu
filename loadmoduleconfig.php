<?php
session_start();


require_once( 'inc/inc.php' );





$mod=trim(simGetParam($_REQUEST,'mod',''));

header("Content-type: text/xml; ; charset=utf-8");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
echo '<?xml version="1.0" encoding="utf-8"?>
';

	
if (file_exists("mod/$mod/config.xml.php"))
	require_once( "mod/$mod/config.xml.php");


?>
