<?

include("opt/kategorijaslike/kategorijaslike.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=trazKatSlike($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_kategorijaslike", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_kategorijaslike", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_kategorijaslike,'opt=kategorijaslike&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorijaslike", "LOADTABLEDATA", "document.location.href='ajx.php?opt=kategorijaslike&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorijaslike", "LOADTABLEDATA", 'tbl_kategorijaslike.loadXML("ajx.php?opt=kategorijaslike&act=list&pagetype='.$pagetype.'");');

}	
?>