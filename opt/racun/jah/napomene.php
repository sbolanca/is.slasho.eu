<?

include("opt/racun/racun.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziNapomeneRacuna($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_racun", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_racun", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_racunnapomena,'opt=racun&act=napomene',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_racun", "LOADTABLEDATA", "document.location.href='ajx.php?opt=racun&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "tbl_racunnapomena", "LOADTABLEDATA", 'tbl_racun.loadXML("ajx.php?opt=racun&act=napomene&pagetype='.$pagetype.'");');

}	
?>