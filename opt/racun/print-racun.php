<?php
include("opt/racun/racun.class.php");


$allHTML='';

$marginbottom=0;

$printPotpis=intval(simGetParam($_REQUEST,"printPotpis",0));


	$ids=str_replace("|",",",trim(simGetParam($_SESSION,'racunIDS',0)));
	$racunOrderingSQL=trim(simGetParam($_SESSION,'racunOrderingSQL',''));
	$SQL=" WHERE s.id IN ($ids) GROUP BY s.id ".$racunOrderingSQL;

$conf=$database->getSimpleListFromQuery("SELECT * FROM configuration");

$UPDV=intval($conf['UPDV']);

// add a page
$osnovica="rs.kolicina*rs.cijena*(1-rs.popust/100)";
$q="SELECT s.*,YEAR(s.datum_izdavanja) as godina, COUNT(rs.id) as cnt,rn.tekst as napomena,"
//." p.adresa as p_adresa,p.primatelj,t.name as tajnik,"
." SUM($osnovica) as osnovica,SUM(rs.stopa_pdv*$osnovica/100) as pdv,SUM((100+rs.stopa_pdv)*$osnovica/100) as iznos "
		."\n ,c.name as operater"
		."\n FROM racun as s"
		."\n LEFT JOIN user as c ON (c.id  =s.userID )"
		."\n LEFT JOIN racun_napomena as rn ON (rn.id  =s.napomenaID )"
		//."\n LEFT JOIN podruznica as p ON (p.id  =s.podruznicaID )"
		//."\n LEFT JOIN user as t ON (t.id  =p.tajnikID )"
		."\n LEFT JOIN racun_stavka as rs ON (rs.racunID  =s.id )"
		."\n $SQL ";
		
	//echo $q;

$database->setQuery($q);
$rows=$database->loadObjectList();


$x=1; 
/*
$html.='<div style="height:20px"></div>';
	$html.='<table cellspacing="4" cellpadding="2" style="font-size:x-small;border:1px solid #000000" >';
	$html.='<tr><td></td><td></td><td style="font-size:small;text-align:right">='.str_replace(".",",",$r->iznos).'</td><td style="font-size:small;text-align:center">='.str_replace(".",",",$r->iznos).'</td></tr>';
	$html.='<tr><td>'.$r->pl_naziv.'</td><td></td><td></td><td style="text-align:center">'.$r->pl_naziv.'</td></tr>';
	$html.='<tr><td>'.str_replace(",","<br/>",$r->pl_sjediste).'</td><td></td><td></td><td></td></tr>';
	$html.='<tr><td>'.$r->pr_naziv.'</td><td style="width:30px;">'.$r->upl_model.'</td><td style="text-align:center">'.$r->upl_ziro.'</td><td style="text-align:center">'.$r->upl_ziro.'</td></tr>';
	$html.='<tr><td>'.str_replace(",","<br/>",$r->pr_sjediste).'</td><td></td><td style="text-align:center">'.$r->upl_broj.'</td><td style="text-align:center">'.$r->upl_model.' '.$r->upl_broj.'</td></tr>';
	$html.='<tr><td></td><td colspan="2" style="text-align:center">'.$r->upl_svrha.'</td><td></td></tr>';
	$html.='<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>';
	$html.='</table>';
*/




foreach($rows as $ix=>$r) {

$html='';

	$r->datum_izdavanja=convertSQLDateTimeToHr($r->datum_izdavanja);
	$r->datum_isporuke=convertSQLDateTimeToHr($r->datum_isporuke);
	$r->datum_dospijeca=convertSQLDateTimeToHr($r->datum_dospijeca);
	
	$r->iznos=makeHRFloat($r->iznos,'',true);
	$r->osnovica=makeHRFloat($r->osnovica,'',true);
	$r->pdv=makeHRFloat($r->pdv,'',true);
	
	// gornji lijevi dio -------------------------------
	$topleft='<table cellspacing="0"  cellpadding="0" >';
	$topleft.='<tr><td height="10"></td></tr>';
	$topleft.='<tr><td class="lrg"><b>'.$r->naziv.'</b></td></tr>';
	$topleft.='<tr><td class="norm">'.$r->adresa.'</td></tr>';
	$topleft.='<tr><td class="norm">'.$r->sjediste.'</td></tr>';
	$topleft.='<tr><td class="norm" height="35">OIB: <b>'.$r->oib.'</b></td></tr>';

	$datumiLeft='<table cellspacing="0"  cellpadding="0" class="small">';
	$datumiLeft.='<tr><td width="120" height="60" class="lrg vmiddle">Račun broj:</td><td class="xlrg clr vmiddle"><b>'.$r->code.'</b></td></tr>';
	$datumiLeft.='<tr><td>Datum izdavanja:</td><td><b>'.$r->datum_izdavanja.'</b></td></tr>';
	$datumiLeft.='<tr><td>Vrijeme izdavanja:</td><td><b>'.$r->vrijeme_izdavanja.'</b></td></tr>';
	$datumiLeft.='<tr><td >Datum isporuke:</td><td><b>'.$r->datum_isporuke.'</b></td></tr>';
	$datumiLeft.='</table>';

	$topleft.='<tr><td style="padding:0">'.$datumiLeft.'</td></tr>';
	$topleft.='</table>';
	//-------------------------------------------------

	// gornji desni dio -------------------------------
//	$topright='<img style="width:330px;height:108px" src="/images/logo.JPG"/><br/><br/>';
	$topright='<table cellspacing="0"  cellpadding="0" >';
	$topright.='<tr><td><img class="logo" src="/files/Image/logo.png"/></td></tr>';
	$topright.='<tr><td class="lrg"><b>'.$conf['naziv_tvrtke'].'</b></td></tr>';
	$topright.='<tr><td class="norm"><b>'.$conf['podnaziv'].'</b></td></tr>';
	$topright.='<tr><td ><b>'.$conf['adresa'].', '.$conf['mjesto'].'</b></td></tr>';
	$topright.='<tr><td height="36">OIB:	<b>'.$conf['OIB'].'</b></td></tr>';


	$datumiRight='<table cellspacing="0"  cellpadding="0"  class="small">';
	$datumiRight.='<tr><td>Datum dospijeća:</td><td><b>'.$r->datum_dospijeca.'</b></td></tr>';
	$datumiRight.='<tr><td>Način plaćanja:</td><td><b>'.$r->nacin_placanja.'</b></td></tr>';
	$datumiRight.='<tr><td>Oznaka operatera:</td><td><b>'.$r->operater.'</b></td></tr>';
	$datumiRight.='</table>';

	$topright.='<tr><td style="padding:0">'.$datumiRight.'</td></tr>';
	$topright.='</table>';
	//-------------------------------------------------



	// footer -----------------------------------------
	$footer='<span class="sml">'.$conf['puni_naziv_tvrtke'].' •
	Upisano u '.$conf['sudreg'].', broj iz registra: '.$conf['MBS'].' • 
	OIB: '.$conf['OIB'].' • 
	Transakcijski račun-IBAN: '.str_replace(" ","",$conf['IBAN']).' • 
	'.$conf['banka'].' • 
	'.($conf['SWIFT']?'SWIFT code: '.$conf['SWIFT'].' •':'').' 
	email: '.$conf['email'].' • telefon: '.$conf['telefon']
	.' • Adresa: '.$conf['adresa'].', '.$conf['mjesto'].'</span>';

	//-------------------------------------------------


	// stavke -----------------------------------------
	$osnovica="rs.kolicina*rs.cijena*(1-rs.popust/100)";
	$database->setQuery("SELECT rs.*, "
		." $osnovica as osnovica,rs.stopa_pdv*$osnovica/100 as pdv,(100+rs.stopa_pdv)*$osnovica/100 as iznos"
		." FROM racun_stavka as rs WHERE rs.racunID=".$r->id
		." ORDER BY rs.ordering");
	$stList=$database->loadObjectList('id');
	$stavke='<table class="stv" cellspacing="0"  cellpadding="0">';
	$stavke.='<tr class="h sml vmiddle">'
	.'<td style="width:2%">Red.<br/>br.</td>'
	.'<td style="width:40%">Opis</td>'
	.'<td align="center" style="width:5%">Mjerna<br/>jedinica</td>'
	.'<td align="center" style="width:5%">Kol.</td>'
	.($UPDV?'<td align="center" style="width:5%">Stopa<br/>PDV</td>':'')
	.'<td align="right" style="width:9%">Jed.cijena<br/>'.($UPDV?'<span class="xxxsml">bez PDV</span>':'').'</td>'
	.'<td align="center" style="width:6%">Popust</td>'
	.'<td align="right" style="width:9%">Iznos<br/>'.($UPDV?'<span class="xxxsml">bez PDV (€)</span>':'').'</td>'
	.($UPDV?'<td align="right" style="width:9%">Iznos PDV<br/><span class="xxxsml">(€)</span></td>':'')
	.($UPDV?'<td align="right" style="width:9%">Za platiti<br/><span class="xxxsml">(€)</span></td></tr>':'');
	$si=1;
	$pdvList=array();
	$osnovicaList=array();
	foreach($stList as $st) {
		$stavke.='<tr><td >'.($si++).'.</td>'
		.'<td >'.nl2br($st->opis).'</td>'
		.'<td align="center">'.$st->mjera.'</td>'
		.'<td align="center">'.$st->kolicina.'</td>'
		.($UPDV?'<td align="center">'.$st->stopa_pdv.'%</td>':'')
		.'<td align="right">'.makeHRFloat($st->cijena,'',true).'</td>'
		.'<td align="center">'.$st->popust.'%</td>'
		.'<td align="right">'.makeHRFloat($st->osnovica,'',true).'</td>'
		.($UPDV?'<td align="right">'.makeHRFloat($st->pdv,'',true).'</td>':'')
		.($UPDV?'<td align="right">'.makeHRFloat($st->iznos,'',true).'</td></tr>':'');
		if(!isset($pdvList[$st->stopa_pdv])) {
			$pdvList[$st->stopa_pdv]=$st->pdv;
			$osnovicaList[$st->stopa_pdv]=$st->osnovica;
		} else {
			$pdvList[$st->stopa_pdv]+=$st->pdv;
			$osnovicaList[$st->stopa_pdv]+=$st->osnovica;
		}
	}
	
	$stavke.='<tr class="bggrey"><td colspan="'.($UPDV?'7':'6').'" align="right"><b>Ukupno:</b></td>'
	.'<td  align="right"><b>'.$r->osnovica.'</b></td>'
	.($UPDV?'<td  align="right"><b>'.$r->pdv.'</b></td>':'')
	.($UPDV?'<td  align="right"><b>'.$r->iznos.'</b></td>':'')
	.'</tr>';
	if($UPDV) foreach($pdvList as $stopa=>$pdvIznos) {
		$stavke.='<tr><td colspan="8" align="right">Osnovica za '.$stopa.'% PDV-a:</td>'
		.'<td colspan="2" align="right"><b>'.makeHRFloat($osnovicaList[$stopa],' €',true).'</b></td></tr>';
		$stavke.='<tr><td colspan="8" align="right">PDV '.$stopa.'%:</td>'
		.'<td colspan="2" align="right"><b>'.makeHRFloat($pdvIznos,' €',true).'</b></td></tr>';
	}
	$stavke.='<tr class="h"><td colspan="'.($UPDV?'8':'6').'" align="right"><b>Ukupno za platiti:</b></td>'
		.'<td colspan="2" align="right"><b>'.$r->iznos.' €'.'</b></td></tr>';
	$stavke.='</table>';

	//-------------------------------------------------

	$poziv=$r->oib;


	$racun='<table class="main" cellspacing="0"  cellpadding="0">';
	$racun.='<tr><td width="50%">'.$topleft.'</td><td>'.$topright.'</td></tr>';
	$racun.='<tr><td colspan="2" height="30"></td></tr>';
	if($r->opis) {
		$racun.='<tr><td colspan="2" style="font-size:small">'.nl2br($r->opis).'</td></tr>';
		$racun.='<tr><td colspan="2" height="20"></td></tr>';
	}
	$racun.='<tr><td colspan="2">'.$stavke.'</td></tr>';
	if(trim($r->napomena)) {
		$racun.='<tr><td colspan="2" height="30"></td></tr>';
		$racun.='<tr><td colspan="2" style="font-size:small">'.($r->napomena).'</td></tr>';
	
	}
	
	$racun.='<tr><td colspan="2" height="30"></td></tr>';
	$racun.='<tr><td colspan="2" style="font-size:small">'.'Pri plaćanju na transakcijski račun koristite IBAN: <b>'.$conf['IBAN'].'</b>, model <b>'.$conf['RacunModel'].'</b> i poziv na broj <b>'.$poziv.'</b></td></tr>';
	
	//************** BARCODE  *****************

require_once("$simConfig_absolute_path/inc/tcpdf/2dbarcodes.php");


	
	$barcode_data="HRVHUB30
EUR
".str_pad(str_replace(",","",str_replace(".","",$r->iznos)),15,'0',STR_PAD_LEFT)."
".$r->naziv."
".$r->adresa."
".$r->sjediste."
".$conf['naziv_tvrtke']."
".$conf['adresa']."
".$conf['mjesto']."
".$conf['IBAN']."
HR00
".$r->oib."

Račun br.".$r->code."
";

		$barcodeFilename='r-'.$r->id.".png";
		if(is_file($simConfig_absolute_path."/files/tmp/barcode/".$barcodeFilename)) unlink($simConfig_absolute_path."/files/tmp/barcode/".$barcodeFilename);
		$barcodeImgArr[]=$barcodeFilename;
		$barcodeSize=5;

		$barcodeImagePadding=12; // @IMPORTANT - make an even number

		$barcode_w = min(10, max(5, $barcodeSize));
        $barcode_h = 2/5 * $barcode_w;
        $barcodeObj = new TCPDF2DBarcode($barcode_data, 'PDF417');
		$barcodeObj->getBarcodePNG($barcode_w, $barcode_h, [0,0,0],$simConfig_absolute_path."/files/tmp/barcode/".$barcodeFilename);
	
	

//**************************************************************
	
	$racun.='<tr><td colspan="2" height="40"></td></tr>';
	$racun.='<tr><td><img width="300" src="/files/tmp/barcode/'.$barcodeFilename.'"/></td>
	<td class="norm">Odgovorna osoba: <b>'.$conf['potpisnik'].'</b>
		
		'.($printPotpis?'<img class="potpisimg"  src="/files/Image/potpis.png"/>':'').'
	</td></tr>';

	$racun.='</table>';

	//----------------------------------------------------

	// uplatnica -----------------------------------------

	$html.='<table cellspacing="0" style="width:700px" cellpadding="0" >';
	$html.='<tr><td colspan="2" style="height:'.(982-$marginbottom).'px">'.$racun.'</td></tr>';
	$html.='<tr><td colspan="2">'.$footer.'</td></tr>';


		


		$html.='</table>';
	//----------------------------------------------------

	if($ix<(count($rows)-1)) $html.='<div class="page-break"></div>';

		$allHTML.=$html;

}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>RAČUNI</title>

<style>
html,body{font-family:sans-serif}
table tr td {vertical-align:top}
table.main{width:700px;}
table.stv{width:100%}
table.stv tr.sml td{font-size:xx-small}
table.stv tr.h {background-color:#d9252e;color:#fff}
.col{background-color:#d9252e;}
.clr{color:#d9252e;}
.bggrey{background-color:#eeeeee;}
table.stv td {border-bottom: 1px solid #eeeeee}

.xlrg{font-size:x-large}
.lrg{font-size:large}
.norm{font-size:14px}
.small{font-size:small}
.sml{font-size:xx-small;}
.xxxsml{font-size:8px;}

.vmiddle,tr.vmiddle td{vertical-align:middle}
.vbottom,tr.vbottom td{vertical-align:bottom}

td{padding:3px 5px;font-size:11px;}
th{padding:3px 5px;font-weight:bold;font-size:11px;background-color:#dddddd}

img.potpisimg{
	margin-top:-14px;
	width:300px;
	height:129px;
	
}
img.logo{
	width:250px;
	height:68px;
}

.page-break	{ display: block; page-break-before: always; }

.potpis {height:40px;border-bottom:dotted;margin:3px 20px 3px 3px}
span.np{font-size:10px}
.img{margin-bottom:15px;font-size:12px;width:350px;text-align:center;float:left;}

</style>

</head>
<body>
<? echo $allHTML; ?>
</body>
</html>
