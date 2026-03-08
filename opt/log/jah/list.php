<?

include("opt/log/log.class.php");

$pagetype=trim(simGetParam($_REQUEST,'pagetype','list'));

$SN=traziLogove($pagetype);

	
	
$res->change( "so_content", $SN['searchoptions']);
$res->javascript( "$('#log_userID').val(".$SN['log_userID'].")");
//if ($SN['searchoptions']) $res->javascript("$('#so_box').show();");
//else $res->javascript("$('#so_box').hide();");			

$res->change( "cnt",  $SN['cnt']);
//$tmpl->addVar( "opt_album", "LINK", $SN['link']);
$res->change("title", $SN['title']);
//$tmpl->addVar( "opt_album", "PAGETYPE", $pagetype);


$res->javascript("loadTable(tbl_log,'opt=log&act=list',".$SN['cnt'].",true);");


?>