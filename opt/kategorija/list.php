<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziKategorije($pagetype);

	
	
$tmpl->addVar( "opt_kategorija", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_kategorija", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_kategorija", "ORD", $SN['ord']);
$tmpl->addVar( "opt_kategorija", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_kategorija_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_kategorija", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_kategorija", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_kategorija", "LINK", $SN['link']);
$tmpl->addVar( "opt_kategorija", "TITLE", $SN['title']);
$tmpl->addVar( "opt_kategorija", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_kategorija');

insertModule($tmpl,1014);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorija", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorija", "LOADTABLEDATA", 'tbl_kategorija.loadXML("ajx.php?opt=kategorija&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_kategorija", "XLSHIDE", 'style="display:none"');
?>