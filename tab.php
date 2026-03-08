<?php
session_start();

require_once( 'inc/inc.php' );
require_once( 'inc/classes/jah.class.php' );

$myID=intval($_SESSION['MM_id']);
$isJah=true;
$isAdmin=intval($_SESSION['MM_admin']);
$isSuper=intval($_SESSION['MM_super']);
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



$actr=str_replace("-","/",$act);

if ($opt) {
	
require_once( 'lang/'.strtolower($simConfig_lang ).'.php' );
require_once( 'lang/'.strtolower($simConfig_lang ).'.vars.php' );
	
if ($sender) $_sssub=$sender."/"; else $_sssub='';


$res=new jahResponse();


$tmpl =createTemplate( '', $isAdmin, false );


if (file_exists( 'opt/'.$opt.'/jah/'.$_sssub.$actr.'.php' ))
	require_once(  'opt/'.$opt.'/jah/'.$_sssub.$actr.'.php' );
else {
	if (file_exists('opt/'.$opt.'/jah/'.$actr.'.php' )) require_once(  'opt/'.$opt.'/jah/'.$actr.'.php' );
	else 
		echo "Jah akcijski fajl\n'".'opt/'.$opt.'/jah/'.$actr.'.php'."' ne postoji!";
}
if (trim($LOG->title)) {
	$LOG->check(true);
	$LOG->store();
}

//$res->go_to("opt=register&act=logout");
$res->printTab();
}
?>
