<?
global $currpage,$opt,$act,$simConfig_RYEAR;

$obrgod=getObracunskaGodina();

$years=simCreateRangeOptionList($simConfig_YEAR-5,$simConfig_YEAR+1,$obrgod);
$years=array_reverse($years);

$stavkaLink=($opt=='stavka')?"javascript:activateCMCommandPOST('stavka','list','searchSForm');":"index.php?opt=stavka";
$tipstavkeLink=($opt=='tipstavke')?"javascript:activateCMCommandPOST('tipstavke','list','searchTForm');":"index.php?opt=tipstavke";
$kategorijastavkeLink=($opt=='kategorijastavke')?"javascript:activateCMCommandPOST('kategorijastavke','list','searchKForm');":"index.php?opt=kategorijastavke";
$racunLink=($opt=='racun')?"javascript:activateCMCommandPOST('racun','list','searchRForm');":"index.php?opt=racun";
$ponudaLink=($opt=='ponuda')?"javascript:activateCMCommandPOST('ponuda','list','searchPForm');":"index.php?opt=ponuda";

$database->setQuery("SELECT * FROM tipstavke");
$tips=$database->loadObjectList();
$database->setQuery("SELECT * FROM kategorijastavke");
$cats=$database->loadObjectList();
$database->setQuery("SELECT * FROM klijent WHERE servis=1");
$klijenti=$database->loadObjectList();
$database->setQuery("SELECT * FROM user WHERE super=0");
$useri=$database->loadObjectList();


$tmpl->addVar("mod_stavka",'racunLink', $racunLink); 
$tmpl->addVar("mod_stavka",'stavkaLink', $stavkaLink); 
$tmpl->addVar("mod_stavka",'ponudaLink', $ponudaLink); 
$tmpl->addVar("mod_stavka",'tipstavkeLink', $tipstavkeLink); 
$tmpl->addVar("mod_stavka",'kategorijastavkeLink', $kategorijastavkeLink); 
$tmpl->addObject("mod_stavka_k", $cats, "row_",true);
$tmpl->addObject("mod_stavka_t", $tips, "row_",true);
$tmpl->addObject("mod_stavka_l", $klijenti, "row_",true);
$tmpl->addObject("mod_stavka_l2", $klijenti, "row_",true);
$tmpl->addObject("mod_stavka_u", $useri, "row_",true);
$tmpl->addObject("mod_stavka_u2", $useri, "row_",true);
$tmpl->addObject("mod_y0",	$years,'ROW_',false);  

$tmpl->addVar("mod_stavka",$opt.'_pagetype', $act); 

if(isset($mainFrame->vars['folder'])) {
	$tmpl->addVar("mod_stavka",'show'.$opt.'ffilter', 'display:block'); 
	for($i=1;$i<6;$i++) 
		$tmpl->addVar("mod_stavka",'ff_'.$i, $mainFrame->vars['folder']->getBojaName($i)); 

}
?>