<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziSpecijalneUpite($pagetype);

	
	
$tmpl->addVar( "opt_specijalniupit", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_specijalniupit", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_specijalniupit", "ORD", $SN['ord']);
$tmpl->addVar( "opt_specijalniupit", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_specijalniupit_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_specijalniupit", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_specijalniupit", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_specijalniupit", "LINK", $SN['link']);
$tmpl->addVar( "opt_specijalniupit", "TITLE", $SN['title']);
$tmpl->addVar( "opt_specijalniupit", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_specijalniupit');

insertModule($tmpl,1014);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_specijalniupit", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_specijalniupit", "LOADTABLEDATA", 'tbl_specijalniupit.loadXML("ajx.php?opt=specijalniupit&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_specijalniupit", "XLSHIDE", 'style="display:none"');
?>