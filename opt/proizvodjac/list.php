<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=trazProizvodjace($pagetype);

	
	
$tmpl->addVar( "opt_proizvodjac", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_proizvodjac", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_proizvodjac", "ORD", $SN['ord']);
$tmpl->addVar( "opt_proizvodjac", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_proizvodjac_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_proizvodjac", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_proizvodjac", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_proizvodjac", "LINK", $SN['link']);
$tmpl->addVar( "opt_proizvodjac", "TITLE", $SN['title']);
$tmpl->addVar( "opt_proizvodjac", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_proizvodjac');

insertModule($tmpl,1014);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_proizvodjac", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_proizvodjac", "LOADTABLEDATA", 'tbl_proizvodjac.loadXML("ajx.php?opt=proizvodjac&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_proizvodjac", "XLSHIDE", 'style="display:none"');
?>