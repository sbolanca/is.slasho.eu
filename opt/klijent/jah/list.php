<?

include("opt/klijent/klijent.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=trazKlijente($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_klijent", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_klijent", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_klijent,'opt=klijent&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_klijent", "LOADTABLEDATA", "document.location.href='ajx.php?opt=klijent&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_klijent", "LOADTABLEDATA", 'tbl_klijent.loadXML("ajx.php?opt=klijent&act=list&pagetype='.$pagetype.'");');

}	
?>