<?

include("opt/user/user.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziOsobe($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_user", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_user", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_user,'opt=user&act=list',".$SN['cnt'].",true);");


?>