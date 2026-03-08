<?

include("opt/kategorijastavke/kategorijastavke.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=trazKatStavke($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_kategorijastavke", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_kategorijastavke", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_kategorijastavke,'opt=kategorijastavke&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_kategorijastavke", "LOADTABLEDATA", "document.location.href='ajx.php?opt=kategorijastavke&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_kategorijastavke", "LOADTABLEDATA", 'tbl_kategorijastavke.loadXML("ajx.php?opt=kategorijastavke&act=list&pagetype='.$pagetype.'");');

}	
?>