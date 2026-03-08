<?
define( '_VALID_SIM', 1 );

DEFINE('_ISO','charset=utf-8');

if ( !file_exists( 'configuration.php' ) || filesize( 'configuration.php' ) < 10 ) {
	echo 'ajme meni';
	exit();
} 
require_once( 'configuration.php' );
require_once( 'opt/settings/settings.config.php' );
include_once( 'inc/version.php' );
include_once( 'globals.php' );
setlocale(LC_ALL,$simConfig_locale); 
error_reporting ($simConfig_error_reporting);
require_once( 'inc/common.php' );
require_once( 'inc/include.php' );
require_once( 'inc/database.php' );
require_once( 'inc/register.php' );
require_once( 'inc/class.php' );
require_once( 'inc/patTemplate/patTemplate.php' );
include_once( 'inc/meta.php' );


if ( $simConfig_offline == 1 ){
	include( 'offline.php' );
	exit();
}


$database = new database( $simConfig_host, $simConfig_user, $simConfig_password, $simConfig_db );
$database->debug( $simConfig_debug );


?>