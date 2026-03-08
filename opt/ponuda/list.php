<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziPonude($pagetype);

	
	
$tmpl->addVar( "opt_ponuda", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_ponuda", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_ponuda", "ORD", $SN['ord']);
$tmpl->addVar( "opt_ponuda", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_ponuda_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_ponuda", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_ponuda", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_ponuda", "LINK", $SN['link']);
$tmpl->addVar( "opt_ponuda", "TITLE", $SN['title']);
$tmpl->addVar( "opt_ponuda", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");
$mainFrame->addHeaderScript("var UPDV=".intval(getConfig('UPDV')).";","UPDV");

printTableHeaderJSVars('tbl_ponuda');

insertModule($tmpl,1102);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_ponuda", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_ponuda", "LOADTABLEDATA", 'tbl_ponuda.loadXML("ajx.php?opt=ponuda&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_ponuda", "XLSHIDE", 'style="display:none"');

		

//$liteboxinit=file_get_contents('jq/prettyPhoto/includeinfooter.src.js');
//$mainFrame->addFooterScript($liteboxinit,"liteboxinit");


?>