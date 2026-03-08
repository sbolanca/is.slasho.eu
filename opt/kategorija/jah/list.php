<?

include("opt/kategorija/kategorija.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziKategorije($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_kategorija", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_kategorija", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_kategorija,'opt=kategorija&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorija", "LOADTABLEDATA", "document.location.href='ajx.php?opt=kategorija&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorija", "LOADTABLEDATA", 'tbl_kategorija.loadXML("ajx.php?opt=kategorija&act=list&pagetype='.$pagetype.'");');

}	
?>