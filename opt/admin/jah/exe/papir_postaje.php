<?
$database->execQuery("UPDATE `izv_papir` AS i,emiter AS e,program AS p SET i.programID=p.id
WHERE i.programID=0
AND i.postaja_code=e.code
AND e.id=p.emiterID
AND p.emisijaID=0
AND i.postaja_code NOT LIKE '0%'
AND i.postaja_code NOT LIKE '1%'");

$database->execQuery("UPDATE `izv_papir` AS i,program AS p SET i.programID=p.id
WHERE i.programID=0
AND i.postaja_code=LPAD(p.id,4,'0')
AND p.emisijaID>0
AND (i.postaja_code LIKE '0%' OR  i.postaja_code LIKE '1%')");

$res->alert("Postaje su povezane s papirnatim izvođenjima (postaja_code je preslikan u programID).");

?>