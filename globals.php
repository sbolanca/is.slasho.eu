<?php
/**
* @version $Id: globals.php,v 1.7 2005/01/24 17:48:18 troozers Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

$raw = phpversion();
list($v_Upper,$v_Major,$v_Minor) = explode(".",$raw);

if (($v_Upper == 4 && $v_Major < 1) || $v_Upper < 4) {
	$_FILES = $HTTP_POST_FILES;
	$_ENV = $HTTP_ENV_VARS;
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_COOKIE = $HTTP_COOKIE_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
	$_SESSION = $HTTP_SESSION_VARS;
	$_FILES = $HTTP_POST_FILES;
}

if (!ini_get('register_globals')) {
	foreach( $_FILES as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_ENV as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_GET as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_POST as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_COOKIE as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_SERVER as $key=>$value) $GLOBALS[$key]=$value;
	foreach( $_SESSION as $key=>$value) $GLOBALS[$key]=$value;
	foreach($_FILES as $key => $value){
		$GLOBALS[$key]=$_FILES[$key]['tmp_name'];
		foreach($value as $ext => $value2){
			$key2 = $key . '_' . $ext;
			$GLOBALS[$key2] = $value2;
		}
	}
}
?>
