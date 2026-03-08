<?
$database->execQuery("DELETE FROM izvodjenje WHERE papir=1");

$database->execQuery("INSERT INTO `izvodjenje` (papir,dbindex,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,serial,rbr,vrijeme_od,vrijeme_do,pocetak)
SELECT '1' as p,id,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,list,rbr,vrijeme_od,vrijeme_do,IF(pocetak<>'',pocetak,NULL) as poc FROM izv_papir");

$res->alert("Papirnata izvođenja su prebačena u tablicu svih izvođenja.");

?>