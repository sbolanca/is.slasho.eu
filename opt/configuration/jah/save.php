<?

$numeric=intval(simGetParam($_REQUEST,'numeric',0));
$decimal=intval(simGetParam($_REQUEST,'decimal',0));
$execjs=trim(simGetParam($_REQUEST,'execjs',''));
$condpageopt=trim(simGetParam($_REQUEST,'condpageopt',''));

$row=new simConfiguration($database);
$row->bind($_POST);

$row->check(true);
$row->store();

$row->load($row->type);

$res->javascript("$('#".$row->type."').html('".$row->value."')");


$res->closeSimpleDialog(3);
$res->alertOK();
if($condpageopt && $execjs && ($pageopt==$condpageopt)) $res->javascript($execjs);

?>