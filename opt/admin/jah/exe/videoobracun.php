<?

		$sqlobrtxtep1="x.bodovi*(x.broj_epizoda/s.broj_epizoda)*t.koeficijent*s.broj_emitiranja";
		$sqlobrtxtep2="x.bodovi*t.koeficijent*s.broj_emitiranja";
		
		$iznos=$database->getResult("SELECT value FROM configuration WHERE type='iznos_glumacke_raspodjele2015'");
		$total=$database->getResult("SELECT 
				SUM(IF(t.epizode=1,$sqlobrtxtep1,$sqlobrtxtep2)) as formula_imdb
				 FROM `video_sudjelovanje2015` AS x
				 LEFT JOIN video2015 AS s ON s.id=x.videoID
				 LEFT JOIN tip_videa AS t ON t.id=s.tipID
				WHERE 
				s.recyclebin=0 AND
				x.recyclebin=0 AND
				s.broj_emitiranja>0");
				
				
				
		$database->setQuery("SELECT a.izvodjacID as id,IF(u.id IS NULL,0,u.id) as userID, u.name,u.naziv as korisnik, u.mbg, u.prezime,u.ime, a.naziv as alias,GROUP_CONCAT(DISTINCT(a.naziv) SEPARATOR '# ') as aliasi,"
		."\n SUM(IF(t.epizode=1,$sqlobrtxtep1,$sqlobrtxtep2)) as formula_imdb,'' as iznos_formula_imdb"
		."\n FROM `video_sudjelovanje2015` AS x"
		."\n LEFT JOIN video2015 AS s ON s.id=x.videoID"
		."\n LEFT JOIN tip_videa AS t ON t.id=s.tipID"
		."\n LEFT JOIN alias as a ON a.id=x.aliasID"
		."\n LEFT JOIN user as u ON u.id=a.izvodjacID"
		."\n WHERE s.recyclebin=0 AND x.recyclebin=0 AND s.broj_emitiranja>0 "
		."\n GROUP BY a.izvodjacID"
		."\n ORDER BY u.prezime,a.naziv");
		
		$rows=$database->loadObjectList();
		
		$fp=fopen("OBRACUN_GLUMACA_2015.TXT","w");
		
		foreach($rows as $row) {
				$txt=$row->mbg."|";
				$txt.=($row->mbg?$row->korisnik:$row->alias)."|";
				$txt.=sprintf("%01.3f",$row->formula_imdb)."|";
				$txt.=sprintf("%01.2f",$row->formula_imdb*$iznos/$total);
				fputs($fp,$txt."\r\n");
			
		}
		fclose($fp);
		$res->alert("Kreirana je datoteka OBRACUN_GLUMACA_2015.TXT");

?>