<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  $isValid = False; 
  if (!empty($UserName)) { 
     $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
if (isset($_POST['changedebug'])) {
	if (isset($_POST['jah_debug0'])) $_SESSION['jah_debug0']="1"; else  $_SESSION['jah_debug0']="0";
	if (isset($_POST['jah_debug1'])) $_SESSION['jah_debug1']="1"; else  $_SESSION['jah_debug1']="0";
	  header("Location: ". $_POST['retrn']); 
}
if (isset($_POST['changeadmin'])) {
	if (isset($_POST['jah_hidemsg'])) $_SESSION['jah_hidemsg']="1";	else  $_SESSION['jah_hidemsg']="0";
	if (isset($_POST['jah_published'])) $_SESSION['jah_published']="1";	else  $_SESSION['jah_published']="0";
	header("Location: ". $_POST['retrn']); 
}
if(!isset($_SESSION['jah_debug0'])) $_SESSION['jah_debug0']="0";
if(!isset($_SESSION['jah_debug1'])) $_SESSION['jah_debug1']="0";
if(!isset($_SESSION['jah_hidemsg'])) $_SESSION['jah_hidemsg']="0";
if(!isset($_SESSION['jah_published'])) $_SESSION['jah_published']="1";


?><?php


$myID=intval($_SESSION['MM_id']);


$isAdmin=true;


include_once( 'inc/inc.php' );
$template=$simConfig_template;


$id=intval(simGetParam($_REQUEST,'id',0));
$act=trim(simGetParam($_REQUEST,'act','show'));
if(!$act) $act='list';
$opt=trim(simGetParam($_REQUEST,'opt','home'));



$lang='hr';
require_once( 'lang/'.strtolower($lang).'.php' );
require_once( 'lang/'.strtolower($lang).'.vars.php' );




$tmpl =createTemplate( 'index.html', $_SESSION['MM_super'], false );

$mainFrame=new simMainFrame($template,intval($_SESSION['MM_admin']),$simConfig_sitename);

$LOG=new simLog($database,$opt,$act,$id,$myID,$_SESSION['MM_super']);

if (($opt=='home')) { 
	switch ($_SESSION['MM_Username']) {
		default: $opt='stavka';
	}
	$act='list';
}




$Register=new simRegistration($database);
$Register->initSession();

// ----------------------------
$return = simGetParam( $_REQUEST, 'return', NULL );

if ($opt == "login") {
	$Register->login();
	if ($return) {
		simRedirect( $return );
	} else {
		simRedirect( 'index.php' );
	}

} else if ($opt == "logout") {
	$Register->logout(false);
	if ($return) {
		simRedirect( $return );
	} else {
		simRedirect( 'index.php' );
	}
}
// --------------------------------

$My = $Register->getUser();
$Permissions=new simPermission();

include( 'templateactions/fronttemplate.php' );




$qstr=isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
if (substr($qstr,0,1)=="&") $qstr=substr($qstr,1);

if ($qstr && substr_count($qstr,"&lang=")) $qstr=str_replace("&lang=".$lang,"",$qstr);
if ($qstr && substr_count($qstr,'lang=')) $qstr=str_replace("lang=".$lang,"",$qstr);
$currpage="index.php?".(($qstr) ? $qstr."&" : "");
$currpage=str_replace("?&","?",$currpage);

$mainContent=getMainContent($tmpl,$opt);


function getModuleContent(&$tmpl,&$module) {
	global $id,$lang,$act,$opt,$database,$myID,$LOG,$mainFrame,$Register,$My,$simConfig_YEAR,$Permissions,$SETTINGS;
	$hideModule=false;
	$cont='';
	$modtemplpath=$mainFrame->getModuleTemplatePath($module);
	if ($modtemplpath) {
		$tmpl->readTemplatesFromInput( $modtemplpath );
		$params=new simParams($module->param);
		if (isset($params->showtest) && !simModuleShowtest($params->showtest)) $hideModule=true;
		else if (is_file("mod/".$module->name."/".$module->name.".php")) include("mod/".$module->name."/".$module->name.".php");
		if (!$hideModule) $cont= $tmpl->getParsedTemplate( $module->name );
		$tmpl->freeTemplate( $module->name, true );
	} else $cont="Modul '".$module->name."' : template file nije pronađen !";
	return $cont;
}

function getMainContent(&$tmpl,$opt) {
	global $id,$lang,$act,$database,$myID,$LOG,$mainFrame,$Permissions,$simConfig_sitename,$Register,$My,$simConfig_YEAR,$SETTINGS;
	$tmplfile="";
	$cont='';
	if (!$Permissions->allow()) $cont='<table width="100%" height="100%"><tr><td style="color:#ff0000" align="center" valign="middle"><strong>Nemate potrebne dozvole za korištenje ove aplikacije!</strong></td></tr></table>';
	else {
		if (is_file("opt/$opt/$opt.php")) include("opt/$opt/$opt.php");
		else $task=$opt;
		//$mainFrame->includeCMActions($opt,'',false);
		$mainFrame->addHeaderScript("var opt='$opt';","opt");
		$mainFrame->addHeaderScript("var allowEdit=".($Permissions->allow('edit') ? 'true' : 'false').";","allowEdit");
		if (!$tmplfile) $tmplfile=$task;
		$templatefile="opt/$opt/$tmplfile.html"; 	
		if (is_file($tmpl->getRoot()."/".$templatefile)) {
			$tmpl->readTemplatesFromInput( $templatefile );
			if (is_file("opt/$opt/$task.php")) include("opt/$opt/$task.php");	
			$mainFrame->replaceHeaaderScript("var pageact='$task';","pageact");
			$cont= $tmpl->getParsedTemplate( "opt_".$opt );
			$tmpl->freeTemplate( "opt_".$opt, true );
			$mainFrame->addHeaderScript("var pagetmpl='".$tmplfile."';","pagetmpl");
		} else $cont="Komponenta: ".$opt." - template file '".$templatefile."' nije pronađen !";
	}
	return $cont;
}

function getPositionContent(&$tmpl,$pos,&$modules) {
	global $template;
	$cont='';
	$mcont=array();
	foreach ($modules as $m) {
		$mc=getModuleContent($tmpl,$m);
		if (trim($mc)) $mcont[]=$mc;
	}
	if (count($mcont)) {
		if (file_exists("tmpl/$template/pos/pos_$pos.html")) {
			$tmpl->readTemplatesFromInput( "pos/pos_$pos.html" );
			$tmpl->addVar("pos_$pos", 'module',$mcont); 
			$cont=$tmpl->getParsedTemplate( "pos_$pos" );
			$tmpl->freeTemplate( "pos_$pos", true );
		} else $cont=implode("\n",$mcont);
	}
	return $cont;
}

if (!$mainFrame->isSuper) $flt=" AND m.super=0 ";
else $flt=' AND m.superhide=0 ';

$query="SELECT m.* FROM module as m "
."\n WHERE  m.position<>'EMPTY' AND m.published=1 ".$flt
."\n ORDER BY m.position,m.ordering";

$database->setQuery($query);
$rows=$database->loadObjectList();




$positions=simMakeNestedArray($rows,'position');



$poscontent=array();
$removepos=array();
foreach (array_keys($positions) as $p) {
	if (count($positions[$p])  && intval($positions[$p][0]->id) && ((!$mainFrame->isSuper && !intval($positions[$p][0]->super)) || $mainFrame->isSuper)) 
		$c=getPositionContent($tmpl,$p,$positions[$p]);
	else $c='';
	if (trim($c)) $poscontent[$p]=$c;
	else $removepos[]=$p;
}

$mainFrame->enableUpload();

$mainFrame->addBodyAction("onLoad","setButtonsHighlight('tmblock');");
$mainFrame->addBodyAction("onLoad","setButtonsHighlight('tmblock2');");

$edits=$database->loadResultArrayText("SELECT CONCAT('\'',opt,'\'') as opt FROM permissions WHERE permission='edit' AND username='".$_SESSION['MM_Username']."'");
$views=$database->loadResultArrayText("SELECT CONCAT('\'',opt,'\'') as opt FROM permissions WHERE username='".$_SESSION['MM_Username']."'");
$mainFrame->addHeaderScript("var edits=[$edits];","edits");
$mainFrame->addHeaderScript("var views=[$views];","views");


$mainFrame->init();

		


$tmpl->addVar("main", "USER", $mainFrame->adminName); 
$tmpl->addVar("main", "OPENNAV", $mainFrame->openNavigation?$mainFrame->openNavigation:$opt); 

$tmpl->addVar("main", "PAGETITLE", $mainFrame->title." - ".$mainFrame->adminName." [".$simConfig_YEAR."]"); 
$tmpl->addVar("main", "BODYACTIONS", $mainFrame->bodyActions); 
tmplAddConditionPairs($tmpl);
$tmpl->addObject( 'metatags', $mainFrame->metatags, 'meta_' );
$tmpl->addObject( 'includedscripts', $mainFrame->scripts, 'script_' );
$tmpl->addObject( 'includedlinks', $mainFrame->links, 'link_' );
 $mainFrame->initOnloadScripts();
$tmpl->addObject( 'headerscripts', $mainFrame->headerscripts, 'headerjs_' );
$tmpl->addObject( 'headerstyles', $mainFrame->headerstyles, 'headerst_' );
$tmpl->addObject( 'footerscripts', $mainFrame->footerscripts, 'footerjs_' );

$tmpl->addVar("main", "currpage", $currpage); 
$tmpl->addVar("main", "LANG", $lang); 

$ablock=file_get_contents('css/admin.html'); 
$tmpl->addVar("main", "ADMINBLOCK", $ablock); 

$layout=$tmpl->getParsedTemplate( 'main' );
$tmpl->freeTemplate( 'main', true );

foreach ($removepos as $p) {
	$layout=str_replace("[__".$p."__]",'',$layout);
}
foreach (array_keys($poscontent) as $p) {
	$layout=str_replace("[__".$p."__]",$poscontent[$p],$layout);
}


$layout=str_replace("[____MAINCONTENT____]",$mainContent,$layout);

echo $layout; 


?>
