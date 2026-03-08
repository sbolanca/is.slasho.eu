<?

include("opt/ponuda/ponuda.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziPonude($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_ponuda", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_ponuda", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_ponuda,'opt=ponuda&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_ponuda", "LOADTABLEDATA", "document.location.href='ajx.php?opt=ponuda&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_ponuda", "LOADTABLEDATA", 'tbl_ponuda.loadXML("ajx.php?opt=ponuda&act=list&pagetype='.$pagetype.'");');

}	
?>