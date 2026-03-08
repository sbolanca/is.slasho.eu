<?
global $currpage,$opt,$act;

$userLink=($opt=='user')?"javascript:activateCMCommandPOST('user','list','searchForm');":"index.php?opt=user";

$tmpl->addVar("mod_user",'userLink', $userLink); 
$tmpl->addVar("mod_user_del",'issuper', $mainFrame->isSuper); 
$tmpl->addVar("mod_punomoc",$opt.'_pagetype', $act); 

?>