<?php
session_start();


require_once( 'inc/inc.php' );
require_once( 'inc/classes/jah.class.php' );

$responsetype=trim(simGetParam($_REQUEST,'responsetype','json'));
$opt=trim(simGetParam($_REQUEST,'opt',''));
$act=str_replace("-","/",trim(simGetParam($_REQUEST,'act','')));



if (file_exists("opt/$opt/service/$act".'.php'))
	if($responsetype=='json') {
	

		require_once( "opt/$opt/service/$act".'.php');

	} else if($responsetype=='xml') {
	
		header('Content-Type: text/xml');

		if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
			header("Content-type: application/xhtml+xml"); } else {
			header("Content-type: text/xml");
		}
		echo("<?xml version='1.0' encoding='utf-8'?>\n");

		require_once(  'opt/'.$opt.'/service/'.$act.'.php' );
	
}
?>