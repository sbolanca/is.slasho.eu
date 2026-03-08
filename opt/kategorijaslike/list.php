<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=trazKatSlike($pagetype);

	
	
$tmpl->addVar( "opt_kategorijaslike", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_kategorijaslike", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_kategorijaslike", "ORD", $SN['ord']);
$tmpl->addVar( "opt_kategorijaslike", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_kategorijaslike_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_kategorijaslike", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_kategorijaslike", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_kategorijaslike", "LINK", $SN['link']);
$tmpl->addVar( "opt_kategorijaslike", "TITLE", $SN['title']);
$tmpl->addVar( "opt_kategorijaslike", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_kategorijaslike');

insertModule($tmpl,1014);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorijaslike", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorijaslike", "LOADTABLEDATA", 'tbl_kategorijaslike.loadXML("ajx.php?opt=kategorijaslike&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_kategorijaslike", "XLSHIDE", 'style="display:none"');
?>