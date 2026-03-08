<?php
session_start();
$hideMSG=intval($_SESSION['jah_hidemsg']);
$defPublish=intval($_SESSION['jah_published']);

require_once( 'inc/inc.php' );
require_once( 'inc/classes/jah.class.php' );

$myID=intval($_SESSION['MM_id']);
$isJah=true;
$isAdmin=intval($_SESSION['MM_admin']);
$isSuper=intval($_SESSION['MM_super']);
$arhiva=trim(simGetParam($_SESSION,'pos_arhiva',''));
$template=$simConfig_template;

$pageopt=trim(simGetParam($_REQUEST,'pageopt',''));
$pageact=trim(simGetParam($_REQUEST,'pageact',''));
$pagetmpl=trim(simGetParam($_REQUEST,'pagetmpl',''));


$tab=trim(simGetParam($_REQUEST,'tab','show'));
$opt=trim(simGetParam($_REQUEST,'opt',''));
$act=trim(simGetParam($_REQUEST,'act',''));
$lang=trim(simGetParam($_REQUEST,'lang',''));
$id=intval(simGetParam($_REQUEST,'id',0));
$cidx=intval(simGetParam($_REQUEST,'cidx',0));
$sender=trim(simGetParam($_REQUEST,'sender',''));
$sugest_elementid=trim(simGetParam($_REQUEST,'elementid',''));
$suggest_string=trim(simGetParam($_REQUEST,'suggest_string',''));
$selectedAction=trim(simGetParam($_REQUEST,'selectedaction',''));
$_selectedactions=trim(simGetParam($_REQUEST,'selectedactions',''));
if (trim($_selectedactions)) $selectedActions=explode('|',$_selectedactions);
else $selectedActions=array();

$LOG=new simLog($database,$opt,$act,$id,$myID,$isSuper);



$act=str_replace("-","/",$act);

if ($opt) {
	
require_once( 'lang/'.strtolower($simConfig_lang ).'.php' );
require_once( 'lang/'.strtolower($simConfig_lang ).'.vars.php' );
	
if ($sender) $_sssub=$sender."/"; else $_sssub='';



$res=new jahResponse();
header('Content-Type: text/xml');

$tmpl =createTemplate( '', $isSuper, false );


if (file_exists( 'opt/'.$opt.'/jah/'.$_sssub.$act.'.php' ))
	require_once(  'opt/'.$opt.'/jah/'.$_sssub.$act.'.php' );
else {
	if (file_exists('opt/'.$opt.'/jah/'.$act.'.php' )) require_once(  'opt/'.$opt.'/jah/'.$act.'.php' );
	else {
		$actt=new jahAction('alert');
		$actt->addBlock("Jah akcijski fajl\n'".'opt/'.$opt.'/jah/'.$act.'.php'."'\nne postoji!");
		$res->addAction($actt);
	}
}
if (trim($LOG->title)) {
	$LOG->check(true);
	$LOG->store();
	foreach($LOG->_subs as $sLog) $sLog->store($LOG->id);
}

//$res->go_to("opt=register&act=logout");
$res->printResponse();
}
?>
