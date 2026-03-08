<?php
session_start();


require_once( 'inc/inc.php' );
require_once( 'inc/classes/jah.class.php' );

$opt=trim(simGetParam($_REQUEST,'opt',''));
$act=str_replace("-","/",trim(simGetParam($_REQUEST,'act','')));
$id=intval(simGetParam($_REQUEST,'id',0));
$term=trim(simGetParam($_REQUEST,'term',''));
$myID=intval($_SESSION['MM_id']);

$isAdmin=intval($_SESSION['MM_admin']);
$isSuper=intval($_SESSION['MM_super']);



	
if (file_exists("opt/$opt/json/$act".'.php'))
	require_once( "opt/$opt/json/$act".'.php');


?>
