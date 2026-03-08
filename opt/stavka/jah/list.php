<?

include("opt/stavka/stavka.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziStavke($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_stavka", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_stavka", "PAGETYPE", $pagetype);
$res->javascript("var folderID=".$SN['folderID'].";");

$res->javascript("loadTable(tbl_stavka,'opt=stavka&act=list&pagetype=$pagetype',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_stavka", "LOADTABLEDATA", "document.location.href='ajx.php?opt=stavka&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_stavka", "LOADTABLEDATA", 'tbl_stavka.loadXML("ajx.php?opt=stavka&act=list&pagetype='.$pagetype.'");');

}	
?>