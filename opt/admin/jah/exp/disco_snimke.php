<?


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
	$res->javascript("showEditPopup('popupdescription');$('.imr_ .progressbar').progressbar({value: 0});canRefresh=false;");
	
	$starttimestamp=mktime();
	$_SESSION['export_disco_startime']=$starttimestamp;
	
} else {
	$starttimestamp=$_SESSION['export_disco_startime'];
}

$step++;

	$database->setQuery("SELECT s.id,s.naziv,s.izvodjac,s.huzip,s.trajanje,s.dxs,s.broj_izvodjenja,s.autori,s.godina,a.naziv as album FROM snimka AS s "
	." LEFT JOIN album as a ON a.id=s.albumID"
	." WHERE s.recyclebin=0 AND s.discoID<1 $limitSQL");
	$rows=$database->loadObjectList();

if(substr_count($simConfig_live_site,'.test')) $tld='test';
else $tld='hr';
$options = array(
   // 'trace' => 1,
    'uri' => 'http://disco.posluh.'.$tld.'/namespace',
    'location' => 'http://disco.posluh.'.$tld.'/services.php',
);
$client = new SOAPClient(null, $options);

if (!$client) $res->alert("Neuspješno spajanje na vanjsku bazu.");
else {

	if(substr_count($simConfig_db,'_test'))  $client->setDBSufix('_test');
	else $client->setDBSufix('');
	if(!$working) $working=intval($client->getNextWorking());
	//echo $client->__getLastResponseHeaders();
	if($working) {


		$sifre=$client->dodajSnimke($rows,$working);

		$cnt=count(array_keys($sifre));
		
		if($total && !count($rows)) $client->createWorkingLog($working);
		
		foreach($sifre as $k=>$v) $database->execQuery("UPDATE snimka SET discoID=$v WHERE id=$k");
		
		$percent=min(100,$limit*$step*100/$total);
		$percentPrint=sprintf("%01.2f",$percent);
		$endtimestamp=mktime();
		
		
		
		$res->javascript("setImpResult('',".count($rows).",".$cnt.",".$cnt.")");
		if (count($rows)) {
			
			$sec=($endtimestamp-$starttimestamp)*(100/$percent-1);
			$endtime=date("H:i:s",$sec-3600);
			$res->javascript("$('.imr_ .endtime').html('Preostalo: $endtime');");
			$res->javascript("$('.imr_ .progressvalue').html('$percentPrint %');$('.imr_ .progressbar').progressbar('value', ".$percentPrint.");");
			$res->javascript("aCMC($step,'admin','exp-disco_snimke','total=$total&working=$working');");
			
		} else {
			unset( $_SESSION['disco_export_startime'] );
			$res->javascript("canRefresh=true;");
			$endtime=date("H:i:s",$endtimestamp);
			$res->javascript("$('.imr_ .endtime').html('Završetak: $endtime');");
			$res->javascript("$('.imr_ .progressvalue').html('');$('.imr_ .progressbar').progressbar('value', 100);");
			$res->alert("Export završen.");
		}
	} else {
		$res->alert("Working=$working .... malo čudno ... export nije pokrenut.");
	}
}
?>