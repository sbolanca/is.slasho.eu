<?

include_once("opt/mail/mail.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziMailove($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_uloga", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_uloga", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_mail,'opt=mail&act=list',".$SN['cnt'].",true);");

if (intval($SN['cnt'])>0) {
	// $tmpl->addVar( "opt_uloga", "LOADTABLEDATA", "document.location.href='ajx.php?opt=uloga&act=list&pagetype=".$pagetype."';");
	$tmpl->addVar( "opt_mail", "LOADTABLEDATA", 'tbl_mail.loadXML("ajx.php?opt=mail&act=list&pagetype='.$pagetype.'");');

}	
?>