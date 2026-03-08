<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziRacune($pagetype);

	
	
$tmpl->addVar( "opt_racun", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_racun", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_racun", "ORD", $SN['ord']);
$tmpl->addVar( "opt_racun", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_racun_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_racun", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_racun", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_racun", "SUM", $SN['sum']);
$tmpl->addVar( "opt_racun", "LINK", $SN['link']);
$tmpl->addVar( "opt_racun", "TITLE", $SN['title']);
$tmpl->addVar( "opt_racun", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");

printTableHeaderJSVars('tbl_racun');

insertModule($tmpl,1102);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_racun", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_racun", "LOADTABLEDATA", 'tbl_racun.loadXML("ajx.php?opt=racun&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_racun", "XLSHIDE", 'style="display:none"');

		

//$liteboxinit=file_get_contents('jq/prettyPhoto/includeinfooter.src.js');
//$mainFrame->addFooterScript($liteboxinit,"liteboxinit");


?>