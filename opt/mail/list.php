<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziMailove($pagetype);

	
	
$tmpl->addVar( "opt_mail", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_mail", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_mail", "ORD", $SN['ord']);
$tmpl->addVar( "opt_mail", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_mail_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_mail", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_mail", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_mail", "LINK", $SN['link']);
$tmpl->addVar( "opt_mail", "TITLE", $SN['title']);
$tmpl->addVar( "opt_mail", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_mail');

insertModule($tmpl,1002);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_mail", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_mail", "LOADTABLEDATA", 'tbl_mail.loadXML("ajx.php?opt=mail&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_mail", "XLSHIDE", 'style="display:none"');
?>