<?php
 session_start();




require_once( 'inc/inc.php' );



require_once( 'lang/'.strtolower($simConfig_lang ).'.php' );
require_once( 'lang/'.strtolower($simConfig_lang ).'.vars.php' );


$template=$simConfig_template;

$scr=trim(simGetParam($_REQUEST,'scr',''));
$id=intval(simGetParam($_REQUEST,'id',0));
$cidx=intval(simGetParam($_REQUEST,'cidx',0));


	$myID=intval($_SESSION['MM_id']);
	$isAdmin=intval($_SESSION['MM_admin']);
	$isSuper=intval($_SESSION['MM_super']);


	
if (file_exists($scr.'.php'))
	require_once( $scr.'.php');


?>