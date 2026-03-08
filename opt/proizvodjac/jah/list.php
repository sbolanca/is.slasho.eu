<?

include("opt/proizvodjac/proizvodjac.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=trazProizvodjace($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_proizvodjac", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_proizvodjac", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_proizvodjac,'opt=proizvodjac&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_proizvodjac", "LOADTABLEDATA", "document.location.href='ajx.php?opt=proizvodjac&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_proizvodjac", "LOADTABLEDATA", 'tbl_proizvodjac.loadXML("ajx.php?opt=proizvodjac&act=list&pagetype='.$pagetype.'");');

}	
?>