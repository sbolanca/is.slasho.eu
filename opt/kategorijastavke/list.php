<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=trazKatStavke($pagetype);

	
	
$tmpl->addVar( "opt_kategorijastavke", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_kategorijastavke", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_kategorijastavke", "ORD", $SN['ord']);
$tmpl->addVar( "opt_kategorijastavke", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_kategorijastavke_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_kategorijastavke", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_kategorijastavke", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_kategorijastavke", "LINK", $SN['link']);
$tmpl->addVar( "opt_kategorijastavke", "TITLE", $SN['title']);
$tmpl->addVar( "opt_kategorijastavke", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_kategorijastavke');

insertModule($tmpl,1102);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorijastavke", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorijastavke", "LOADTABLEDATA", 'tbl_kategorijastavke.loadXML("ajx.php?opt=kategorijastavke&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_kategorijastavke", "XLSHIDE", 'style="display:none"');
?>