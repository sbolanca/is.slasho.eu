<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=trazTipStavke($pagetype);

	
	
$tmpl->addVar( "opt_tipstavke", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_tipstavke", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_tipstavke", "ORD", $SN['ord']);
$tmpl->addVar( "opt_tipstavke", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_tipstavke_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_tipstavke", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_tipstavke", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_tipstavke", "LINK", $SN['link']);
$tmpl->addVar( "opt_tipstavke", "TITLE", $SN['title']);
$tmpl->addVar( "opt_tipstavke", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_tipstavke');

insertModule($tmpl,1102);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_tipstavke", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_tipstavke", "LOADTABLEDATA", 'tbl_tipstavke.loadXML("ajx.php?opt=tipstavke&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_tipstavke", "XLSHIDE", 'style="display:none"');
?>