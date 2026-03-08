<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=trazKlijente($pagetype);

	
	
$tmpl->addVar( "opt_klijent", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_klijent", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_klijent", "ORD", $SN['ord']);
$tmpl->addVar( "opt_klijent", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_klijent_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_klijent", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_klijent", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_klijent", "LINK", $SN['link']);
$tmpl->addVar( "opt_klijent", "TITLE", $SN['title']);
$tmpl->addVar( "opt_klijent", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_klijent');

insertModule($tmpl,1014);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_klijent", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_klijent", "LOADTABLEDATA", 'tbl_klijent.loadXML("ajx.php?opt=klijent&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_klijent", "XLSHIDE", 'style="display:none"');
?>