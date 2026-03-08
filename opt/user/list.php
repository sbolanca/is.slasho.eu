<?

include_once("opt/user/user.class.php");

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziOsobe($pagetype);

	
$tmpl->addVar( "opt_user", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_user", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_user", "ORD", $SN['ord']);

$tmpl->addVar( "opt_user_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_user", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_user", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_user", "LINK", $SN['link']);
$tmpl->addVar( "opt_user", "TITLE", $SN['title']);
$tmpl->addVar( "opt_user", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

if($SN['link']) {
	
	$lA=explode("=",$SN['link']);
	if(count($lA)==2)
		$mainFrame->addBodyAction("onLoad","setFieldsPair('usrPTField','".$lA[0]."','".$lA[1]."');");
}

printTableHeaderJSVars('tbl_user');



insertModule($tmpl,1004);

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_user", "LOADTABLEDATA", "document.location.href='ajx.php?opt=user&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_user", "LOADTABLEDATA", 'tbl_user.loadXML("ajx.php?opt=user&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_user", "XLSHIDE", 'style="display:none"');


?>