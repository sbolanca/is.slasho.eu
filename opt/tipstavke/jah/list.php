<?

include("opt/tipstavke/tipstavke.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=trazTipStavke($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_tipstavke", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_tipstavke", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_tipstavke,'opt=tipstavke&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_tipstavke", "LOADTABLEDATA", "document.location.href='ajx.php?opt=tipstavke&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_tipstavke", "LOADTABLEDATA", 'tbl_tipstavke.loadXML("ajx.php?opt=tipstavke&act=list&pagetype='.$pagetype.'");');

}	
?>