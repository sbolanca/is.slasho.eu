<?
function s($s) { return mysql_real_escape_string($s); }

$limit=1000;

if($limit) $limitSQL="LIMIT $limit";
else $limitSQL='';

$step=$id;

$working=intval(simGetParam($_REQUEST,'working',0));
$total=intval(simGetParam($_REQUEST,'total',0));


if(!$step) {
	$total=intval($database->getResult("SELECT COUNT(id) FROM snimka WHERE recyclebin=0 AND discoID<1"));
	
	$tmpl->readTemplatesFromInput( "opt/admin/jah/progress.html");
	
	$tmpl->addVar("opt_admin", 'title',"Export $total snimki");
	$tmpl->addVar("opt_admin", 'now',date("H:i:s"));
	$cont= $tmpl->getParsedTemplate("opt_admin");
	
	$res->change('popuptitle','Export');
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');$('.imr .progressbar').progressbar({value: 0});canRefresh=false;");
	
	$starttimestamp=mktime();
	$_SESSION['export_disco_startime']=$starttimestamp;
	
} else {
	$starttimestamp=$_SESSION['export_disco_startime'];
}

$step++;

	$database->setQuery("SELECT s.*,a.naziv as album FROM snimka AS s "
	." LEFT JOIN album as a ON a.id=s.albumID"
	." WHERE s.recyclebin=0 AND s.discoID<1 $limitSQL");
	$rows=$database->loadObjectList();


if($simConfig_live_site=='http://is-test.huzip.test') $database2 = new database( $simConfig_host, $simConfig_user, $simConfig_password, 'diskografija',false );
else if($simConfig_live_site=='http://is.huzip.hr') $database2 = new database( 'disco.posluh.hr', 'diskografija', 'n6amv8dasfsret4', 'diskografija',false );
else die('Nedozvoljeni server');

if (!$database2->_connected) $res->alert("Neuspješno spajanje na vanjsku bazu.");
else {

	$lastID=intval($database2->insertid());

	if(!$working) {
		$working=intval($database2->getResult("SELECT MAX(working) AS w FROM snimka"));
		if($working) $working++;
	}
	if($working) {


		$sifre=array();


		$i=0;
		foreach($rows as $row) {
			$database2->execQuery("INSERT INTO snimka (zoran,naziv,izvodjac,autori,godina,album,dxs,trajanje,working,actstamp) VALUES("
			."'".s($row->huzip)."',"
		//	."'".$row->broj_izvodjenja."',"
			."'".s($row->naziv)."',"
			."'".s($row->izvodjac)."',"
			."'".s($row->autori)."',"
			."'".$row->godina."',"
			."'".s($row->album)."',"
			."'".$row->dxs."',"
			."'".$row->trajanje."',"
			."'".$working."',"
			."NOW())");
			$discoID=intval($database2->insertid());
			if($discoID && !($lastID==$discoID)) {
				
				$sifre[$row->id]=$discoID;
				$i++;
			}
			$lastID=$id;
		}
		if($total && !count($rows)) $database2->execQuery("INSERT INTO `log` (userID,title,subject,details,opt,act,dbindex,created,user,ip, actionpath,super ) (SELECT '1' as ud, 'IMPORT #$working' as tt,CONCAT(naziv,' / ',izvodjac) as sub, 'Aplikacijski import ".date("d.m.Y H:i")."' as dt,'snimka' as o,'#IMPORT' as a,id,NOW() as cr,'Slaven Bolanča' as us,'' as ip, '' as ap,'1' as super FROM snimka WHERE working=$working)");
		
		
		
		
		$database = new database( $simConfig_host, $simConfig_user, $simConfig_password, $simConfig_db );
		foreach($sifre as $k=>$v) $database->execQuery("UPDATE snimka SET discoID=$v WHERE id=$k");
		
		$percent=min(100,$limit*$step*100/$total);
		$percentPrint=sprintf("%01.2f",$percent);
		$endtimestamp=mktime();
		
		$res->javascript("setImpResult('',".count($rows).",".$i.",".$i.")");
		if (count($rows)) {
			
			$sec=($endtimestamp-$starttimestamp)*(100/$percent-1);
			$endtime=date("H:i:s",$sec-3600);
			$res->javascript("$('.imr .endtime').html('Preostalo: $endtime');");
			$res->javascript("$('.imr .progressvalue').html('$percentPrint %');$('.imr .progressbar').progressbar('value', ".$percentPrint.");");
			$res->javascript("aCMC($step,'admin','exp-disco_snimke','total=$total&working=$working');");
			
		} else {
			unset( $_SESSION['disco_export_startime'] );
			$res->javascript("canRefresh=true;");
			$endtime=date("H:i:s",$endtimestamp);
			$res->javascript("$('.imr .endtime').html('Završetak: $endtime');");
			$res->javascript("$('.imr .progressvalue').html('');$('.imr .progressbar').progressbar('value', 100);");
			$res->alert("Export završen.");
		}
	} else {
		$res->alert("Working=$working .... malo čudno ... export nije pokrenut.");
	}
}
?>