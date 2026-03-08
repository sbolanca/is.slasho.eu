<?php
session_start();

$trTag='row';
$tdTag='cell';
$bodyTag='rows';
$tblStylesTransform=array();
$showTableColors=true;

include_once( 'inc/version.php' );
include_once( 'globals.php' );
require_once( 'configuration.php' );
require_once( 'opt/settings/settings.config.php' );
error_reporting ($simConfig_error_reporting);
require_once( 'inc/common.php' );
require_once( 'inc/include.php' );
require_once( 'inc/ajxdatabase.php' );

$database = new database( $simConfig_host, $simConfig_user, $simConfig_password, $simConfig_db );


$myID=intval($_SESSION['MM_id']);
$isJah=true;
$isSuper= intval($_SESSION['MM_super']);
$isAdmin= intval($_SESSION['MM_admin']);
$arhiva=trim(simGetParam($_SESSION,'pos_arhiva',''));

$opt=trim(simGetParam($_REQUEST,'opt',''));
$act=str_replace("-","/",trim(simGetParam($_REQUEST,'act','')));
$id=intval(simGetParam($_REQUEST,'id',0));
$posStart=intval(simGetParam($_REQUEST,'posStart',0));
$count=intval(simGetParam($_REQUEST,'count',0));
$total_count=intval(simGetParam($_REQUEST,'total_count',0));

header('Content-Type: text/xml');

	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
  		header("Content-type: application/xhtml+xml"); } else {
  		header("Content-type: text/xml");
	}
	echo("<?xml version='1.0' encoding='utf-8'?>\n");

if (file_exists( 'opt/'.$opt.'/ajx/'.$act.'.php' ))
	require_once(  'opt/'.$opt.'/ajx/'.$act.'.php' );
else {
		$msg="Ajax akcijski fajl\n'".'opt/'.$opt.'/ajx/'.$act.'.php'."'\nne postoji!";
}

?>
