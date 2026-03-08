<?
global $currpage,$opt,$act;




$specijalniupitLink=($opt=='specijalniupit')?"javascript:activateCMCommandPOST('specijalniupit','list','searchForm');":"index.php?opt=specijalniupit";
$zupanijaLink=($opt=='zupanija')?"javascript:activateCMCommandPOST('zupanija','list','searchZForm');":"index.php?opt=zupanija";
$regijaLink=($opt=='regija')?"javascript:activateCMCommandPOST('regija','list','searchRForm');":"index.php?opt=regija";
$kategorijaLink=($opt=='kategorija')?"javascript:activateCMCommandPOST('kategorija','list','searchKForm');":"index.php?opt=kategorija";
$proizvodjacLink=($opt=='proizvodjac')?"javascript:activateCMCommandPOST('proizvodjac','list','searchPForm');":"index.php?opt=proizvodjac";
$klijentLink=($opt=='klijent')?"javascript:activateCMCommandPOST('klijent','list','searchLForm');":"index.php?opt=klijent";
$tipobjektaLink=($opt=='tipobjekta')?"javascript:activateCMCommandPOST('tipobjekta','list','searchTForm');":"index.php?opt=tipobjekta";
$kategorijaslikeLink=($opt=='kategorijaslike')?"javascript:activateCMCommandPOST('kategorijaslike','list','searchKSForm');":"index.php?opt=kategorijaslike";

$database->setQuery("SELECT * FROM regija");
$regije=$database->loadObjectList();

$tmpl->addVar("mod_tools",'specijalniupitLink', $specijalniupitLink); 
$tmpl->addVar("mod_tools",'zupanijaLink', $zupanijaLink); 
$tmpl->addVar("mod_tools",'regijaLink', $regijaLink); 
$tmpl->addVar("mod_tools",'kategorijaLink', $kategorijaLink); 
$tmpl->addVar("mod_tools",'proizvodjacLink', $proizvodjacLink); 
$tmpl->addVar("mod_tools",'klijentLink', $klijentLink); 
$tmpl->addVar("mod_tools",'tipobjektaLink', $tipobjektaLink); 
$tmpl->addVar("mod_tools",'kategorijaslikeLink', $kategorijaslikeLink); 
$tmpl->addObject("mod_tools_r", $regije, "row_",true);
?>