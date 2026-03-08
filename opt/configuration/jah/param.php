<?
require_once( 'inc/classes/config.class.php' );
	
	$conf=$database->getSimpleListFromQuery("SELECT type, value FROM configuration");
	
	$conf=new Config('configuration','param',$tmpl,$conf);
	$conf->printConfigForm($res,"Konfiguracija sustava",'paramsave');
	

?>