<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$conf=$database->getSimpleListFromQuery("SELECT type, value FROM configuration");

$tmpl->addVars( "opt_configuration", $conf, "c_",true);

insertModule($tmpl,14);

$mainFrame->setTitle("Konfiguracija");

?>