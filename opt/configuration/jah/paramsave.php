<?
require_once( 'inc/classes/config.class.php' );

	$blank=array();
	
	$conf=new Config('configuration','param',$tmpl,$blank);
	$arr=$conf->getConfigArray();
	foreach($arr as $k=>$v) {
		$database->execQuery("INSERT INTO configuration VALUES ('$k','$v')");
		
	}
	$res->javascript("window.location.reload( true );");


?>