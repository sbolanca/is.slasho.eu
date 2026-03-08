<?

$myID=$mainFrame->adminID;
$isSuper=$mainFrame->isSuper;
$isAdmin=$mainFrame->isAdmin;

$SN=traziStavke($pagetype);
if (!isset($_SESSION['tblF_stavka_fields'])) 
		$_SESSION['tblF_stavka_fields']=$SETTINGS['tblF_stavka_fields'];


$sessFolder=intval(simGetParam($_SESSION,'lastOpenFolderStavka',0));
$tmpl->addVar( "opt_stavka", "lastOpenFolder", $sessFolder);
$mainFrame->addHeaderScript("var optF='stavka';","optF");
$mainFrame->addHeaderScript("var folderID=".$SN['folderID'].";","folderID");
$mainFrame->addHeaderScript("var startLastOpenFolder=$sessFolder;","startLastOpenFolder");
setFolderKazalo($SN,$tmpl,'stavka');
if(isset($SN['folder'])) $mainFrame->vars['folder']=$SN['folder'];
printTableHeaderJSVars('tblF_stavka');
	
$sessClipboard=trim(simGetParam($_SESSION,'sta_clipboard',''))	;
$tmpl->addVar( "opt_stavka", "CLIPCNT", $sessClipboard?(substr_count($sessClipboard,",")+1):0);	
$mainFrame->addHeaderScript("var clipboard=[".$sessClipboard."];","clipboard");

$tmpl->addVar( "opt_stavka", "searchoptions", $SN['searchoptions']);
if (!$SN['searchoptions']) $tmpl->addVar( "opt_stavka", "sohide", 'style="display:none"');			

$tmpl->addVar( "opt_stavka", "ORD", $SN['ord']);
$tmpl->addVar( "opt_stavka", $SN['ord']."X", 'selected');

$tmpl->addVar( "opt_stavka_kazalo", 'ISSUPER', $mainFrame->isSuper);
if (!$mainFrame->isSuper) $tmpl->addVar( "opt_stavka", "XLSHIDE", 'style="display:none"');
$tmpl->addVar( "opt_stavka", "CNT", $SN['cnt']);
$tmpl->addVar( "opt_stavka", "LINK", $SN['link']);
$tmpl->addVar( "opt_stavka", "TITLE", $SN['title']);
$tmpl->addVar( "opt_stavka", "PAGETYPE", $pagetype);
$mainFrame->addHeaderScript("var pagetype='".$pagetype."';","pagetype");
$mainFrame->addHeaderScript("var folderID=".$SN['folderID'].";","folderID");

if($SN['link']) {
	
	$lA=explode("=",$SN['link']);
	if(count($lA)==2)
		$mainFrame->addBodyAction("onLoad","setFieldsPair('staPTField','".$lA[0]."','".$lA[1]."');");
}
setFolderKazalo($SN,$tmpl,'snimka');
if(isset($SN['folder'])) $mainFrame->vars['folder']=$SN['folder'];


printTableHeaderJSVars('tbl_stavka');

insertModule($tmpl,1102);


if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_stavka", "LOADTABLEDATA", "document.location.href='ajx.php?opt=instrument&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_stavka", "LOADTABLEDATA", 'tbl_stavka.loadXML("ajx.php?opt=stavka&act=list&pagetype='.$pagetype.'");');

}	else $tmpl->addVar( "opt_stavka", "XLSHIDE", 'style="display:none"');

$mainFrame->includeScript("js/folder.js","folderJs");
?>