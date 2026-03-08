<?
function getSubSum(&$rows,$k) {
	$sum=intval($rows[$k]->izvsum);
	$subA=explode(",",$rows[$k]->ids);
	foreach($subA as $ix) if(isset($rows[$ix])) $sum=$sum+getSubSum($rows,$ix);
	return $sum;
}

function calculateIzvodjenje( $tbl ) {
	global $database;
	$database->execQuery("TRUNCATE TABLE  `snimka_izvodjenje`");
	/*$database->execQuery("INSERT INTO snimka_izvodjenje (id,broj_izvodjenja)"
	." SELECT s.id,SUM(p.broj_izvodjenja) as sm FROM snimka AS s"
	." LEFT JOIN  $tbl AS p ON p.snimkaID=s.id"
	." GROUP BY s.id");
	*/
	$database->execQuery("INSERT INTO snimka_izvodjenje (id,broj_izvodjenja)"
	." SELECT snimkaID,SUM(broj_izvodjenja) as sm FROM $tbl AS s"
	." GROUP BY snimkaID");


	$database->setQuery("SELECT l.newID,SUM(i.broj_izvodjenja) as izvsum, GROUP_CONCAT(l.id) as ids,'0' as total FROM snimka_link AS l"
	." LEFT JOIN snimka_izvodjenje AS i ON i.id=l.id"
	." GROUP BY newID");
	$rows=$database->loadObjectList('newID');

	foreach($rows as $k=>$row) {
		$database->execQuery("INSERT IGNORE INTO snimka_izvodjenje (id) VALUES ($k)");
		$rows[$k]->total=getSubSum($rows,$k);
		//if(!($rows[$k]->izvsum==$rows[$k]->total)) print_r($rows[$k]);
		if(intval($rows[$k]->total)) $database->execQuery("UPDATE snimka_izvodjenje SET broj_izvodjenja=broj_izvodjenja+".intval($rows[$k]->total)." WHERE id=".$k);
	}
}
$database->execQuery("UPDATE snimka SET broj_izvodjenja=0"); 
calculateIzvodjenje( 'izv_papir' );
$database->execQuery("UPDATE snimka AS s,snimka_izvodjenje AS i SET s.broj_izvodjenja=i.broj_izvodjenja WHERE s.id=i.id");
calculateIzvodjenje( 'izv_airplay' );
$database->execQuery("UPDATE snimka AS s,snimka_izvodjenje AS i SET s.broj_izvodjenja=s.broj_izvodjenja+i.broj_izvodjenja WHERE s.id=i.id");



$res->alert("Broj izvođenja je osvježen (REKURZIJA).");

?>