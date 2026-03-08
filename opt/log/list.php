<?

include("opt/log/log.class.php");

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziLogove($pagetype);

$mainFrame->vars['SN']=$SN;
	
$tmpl->addVar( "opt_log", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_log", "sohide", 'style="display:none"');			



$tmpl->addVar( "opt_log_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_log", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_log", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_log", "LINK", $SN['link']);
$tmpl->addVar( "opt_log", "TITLE", $SN['title']);
$tmpl->addVar( "opt_log", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_log');


insertModule($tmpl,1010);

if (!intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_log", "LOADTABLEDATA", "document.location.href='ajx.php?opt=log&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_log", "LOADTABLEDATA", 'tbl_log.loadXML("ajx.php?opt=log&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_log", "XLSHIDE", 'style="display:none"');
?>