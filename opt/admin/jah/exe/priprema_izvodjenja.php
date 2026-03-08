<?



function getSubSum(&$rows,$k) {
	$kA=explode('-',$k);
	$sum=intval($rows[$k]->izvsum);
	$subA=explode(",",$rows[$k]->ids);
	foreach($subA as $ix) if(isset($rows[$ix."-".$kA[1]])) {
		$ret=getSubSum($rows,$ix."-".$kA[1]);
		$sum=$sum+$ret;
	}

	return $sum;
}

$database->setQuery("SELECT id,code FROM emiter");
$emiteri=$database->loadObjectList('id');
	//--------------------------
	
	//reset tablice izvođenja
	$database->execQuery("TRUNCATE TABLE  `izvodjenje_emiter_snimka`");
	$database->execQuery("ALTER TABLE  `izvodjenje_emiter_snimka` DROP INDEX  `snimkaID`");
	//prebacivanje podataka 

	$database->execQuery("INSERT INTO izvodjenje_emiter_snimka (snimkaID,emiterID,code,broj_izvodjenja)
	SELECT i.snimkaID,e.id,e.code,SUM(i.broj_izvodjenja) as broj FROM izvodjenje AS i 
   LEFT JOIN program AS p ON p.id=i.programID
   LEFT JOIN emiter AS e ON e.id=p.emiterID
   GROUP BY i.snimkaID,e.id");
	
$database->execQuery("ALTER TABLE  `izvodjenje_emiter_snimka` ADD INDEX (  `snimkaID` )");
	
	

	

	//kalkuriranje spojenih snimki
	$database->setQuery("SELECT l.newID,i.emiterID,CONCAT(newID,'-',i.emiterID) as kod,"
		." SUM(i.broj_izvodjenja) as izvsum, GROUP_CONCAT(DISTINCT(l.id)) as ids,'0' as total"
		." FROM snimka_link AS l"
		." LEFT JOIN izvodjenje_emiter_snimka AS i ON (i.snimkaID=l.id)"
		." WHERE i.emiterID IS NOT NULL"
		." GROUP BY l.newID,i.emiterID");
	$rows=$database->loadObjectList('kod');

		foreach($rows as $k=>$row) {
			$kA=explode('-',$k);
			$r=getSubSum($rows,$k);
			$rows[$k]->total=$r;
		//	print_r($rA);
			//if(!($rows[$k]->izvsum==$rows[$k]->total)) print_r($rows[$k]);
			if($rows[$k]->total*1>0) {
				$database->execQuery("INSERT IGNORE INTO izvodjenje_emiter_snimka (snimkaID,emiterID,code) VALUES (".$kA[0].",".$kA[1].",'".$emiteri[$kA[1]]->code."')");
				$database->execQuery("UPDATE izvodjenje_emiter_snimka SET broj_izvodjenja=broj_izvodjenja+".(1*$rows[$k]->total)
				." WHERE emiterID=".$kA[1]." AND snimkaID=".$kA[0]);
			}
		}

		
	

	$res->alert("Broj izvođenja snimaka po postaji (tablica izvodjenje_emiter_snimka) je izračunat (REKURZIJA).");
	
	$cont=
"SET SESSION group_concat_max_len = 1000000;
INSERT INTO izvodjenje_disco
SELECT s.discoID,p.broj_izvodjenja,p.ponder,
GROUP_CONCAT(CONCAT(i.code,',',i.broj_izvodjenja) SEPARATOR '|') as po_postaji
FROM `snimka_ponder` AS p
LEFT JOIN snimka AS s ON s.id=p.snimkaID
LEFT JOIN izvodjenje_emiter_snimka AS i ON i.snimkaID=s.id
WHERE p.obracunID=2 AND s.discoID>0 AND s.recyclebin=0
GROUP BY s.id;";
	
	$res->openSimpleDialog("SQL za export izvođenja po postajama za disco",'<pre style="font-size:11px;padding:10px">'.$cont.'</pre>',600,1,'blue-light');
	
?>