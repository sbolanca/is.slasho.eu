<?
global $currpage;
$debug=intval(simGetParam($_GET,'debug',0));
if ($debug || ($mainFrame->adminId==1)) {

	if ($mainFrame->jah_debug0) $tmpl->addVar("mod_debug", "JDB0", 'checked'); 
	if ($mainFrame->jah_debug1) $tmpl->addVar("mod_debug", "JDB1", 'checked'); 
	
	$tmpl->addVar("mod_debug", "CURRENTPAGE", $currpage); 
	$tmpl->addVar("mod_debug",'lang', $lang); 
	
} else $hideModule=true;

?>