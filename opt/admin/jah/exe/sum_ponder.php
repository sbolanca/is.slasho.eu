<?



function getSubSum(&$rows,$k) {
	$sum=intval($rows[$k]->izvsum);
	$sump=intval($rows[$k]->ponsum);
	$subA=explode(",",$rows[$k]->ids);
	foreach($subA as $ix) if(isset($rows[$ix])) {
		$ret=getSubSum($rows,$ix);
		$sum=$sum+$ret[0];
		$sump=$sump+$ret[1];
	}
	$mainret=array();
	$mainret[]=$sum;
	$mainret[]=$sump;
	return $mainret;
}
if(!intval($database->getResult("SELECT COUNT(grupaID) FROM obracun_koeficijent WHERE obracunID=".$selectedAction))) 
	$res->alert("Nisu postavljeni koeficijenti za vrste snimke!");
else if(!intval($database->getResult("SELECT COUNT(emiterID) FROM obracun_ponder WHERE obracunID=".$selectedAction))) 
	$res->alert("Nisu postavljeni ponderi za postaje!");
else {
	//--------------------------
	$database->execQuery("UPDATE snimka SET broj_izvodjenja=0");

	//sređivanje neispravnih kodova postaja
	$database->execQuery("UPDATE `izv_papir` SET postaja_code=LPAD(postaja_code,4,'0') WHERE LENGTH(postaja_code)<4 AND postaja_code NOT LIKE 'H%'");
	//popravljanje polja programID
	$database->execQuery("UPDATE `izv_papir` as i, program AS p SET i.programID=p.id WHERE (i.postaja_code LIKE '0%' OR i.postaja_code LIKE '1%') AND i.postaja_code=LPAD(p.id,4,'0') AND p.emisijaID>0");
	$database->execQuery("UPDATE `izv_papir` as i, program AS p,emiter AS e SET i.programID=p.id WHERE (i.postaja_code NOT LIKE '0%' AND i.postaja_code NOT LIKE '1%') AND p.emiterID=e.id AND i.postaja_code=e.code AND p.emisijaID=0");

	//reset tablice izvođenja
	$database->execQuery("TRUNCATE TABLE  `izvodjenje`");

	//prebacivanje podataka iz izv_papir i izv_airplay

	$database->execQuery("INSERT INTO `izvodjenje` (papir,dbindex,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,serial,rbr,vrijeme_od,vrijeme_do,pocetak)"
	." SELECT '0' as p,id,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,serial,redak,vrijeme_od,vrijeme_do,pocetak FROM izv_airplay");
	$database->execQuery("INSERT INTO `izvodjenje` (papir,dbindex,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,serial,rbr,vrijeme_od,vrijeme_do,pocetak)"
	." SELECT '1' as p,id,snimkaID,programID,trajanje,broj_izvodjenja,vrsta,list,rbr,vrijeme_od,vrijeme_do,IF(pocetak<>'',pocetak,NULL) as poc FROM izv_papir");


	//uklanjanje podataka od prethodnog obračuna
	$database->execQuery("DELETE FROM snimka_ponder WHERE obracunID=$selectedAction");


	//izračun pondera za svaku snimku
	$database->execQuery("INSERT INTO snimka_ponder (obracunID,snimkaID,ponder,broj_izvodjenja)"
	." SELECT '$selectedAction' AS oID,i.snimkaID,SUM(TIME_TO_SEC(i.trajanje)*i.broj_izvodjenja*o.ponder*k.koeficijent) as pvalue, SUM(i.broj_izvodjenja) as bvalue"
	." FROM izvodjenje AS i"
	." LEFT JOIN program AS p ON p.id=i.programID"
	." LEFT JOIN obracun_ponder AS o ON (o.obracunID=$selectedAction AND o.emiterID=p.emiterID)"
	." LEFT JOIN obracun_koeficijent AS k ON (k.obracunID=$selectedAction AND k.grupaID=o.grupaID AND k.vrsta=i.vrsta)"
	." GROUP BY i.snimkaID");

	//kalkuriranje spojenih snimki
	$database->setQuery("SELECT l.newID,SUM(i.broj_izvodjenja) as izvsum,SUM(i.ponder) as ponsum, GROUP_CONCAT(l.id) as ids,'0' as total ,'0' as ptotal FROM snimka_link AS l"
		." LEFT JOIN snimka_ponder AS i ON (i.snimkaID=l.id AND i.obracunID=$selectedAction)"
		." GROUP BY l.newID");
	$rows=$database->loadObjectList('newID');

		foreach($rows as $k=>$row) {
			$database->execQuery("INSERT IGNORE INTO snimka_ponder (obracunID,snimkaID) VALUES ($selectedAction,$k)");
			$rA=getSubSum($rows,$k);
			$rows[$k]->total=$rA[0];
			$rows[$k]->ptotal=$rA[1];
		//	print_r($rA);
			//if(!($rows[$k]->izvsum==$rows[$k]->total)) print_r($rows[$k]);
			if(($rows[$k]->total*1>0) || ($rows[$k]->ptotal*1>0)) 
				$database->execQuery("UPDATE snimka_ponder SET broj_izvodjenja=broj_izvodjenja+".(1*$rows[$k]->total).",ponder=ponder+".(1*$rows[$k]->ptotal)." WHERE obracunID=$selectedAction AND snimkaID=".$k);
		}

		
	//osvježavanje polja broja izvođenja u tablici snimka
	$database->execQuery("UPDATE snimka AS s,snimka_ponder AS i SET s.broj_izvodjenja=i.broj_izvodjenja WHERE s.id=i.snimkaID AND i.obracunID=$selectedAction");


	$res->alert("Ponderi su izračunati. Broj izvođenja je osvježen. (REKURZIJA)");
}
?>