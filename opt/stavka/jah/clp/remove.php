<?

$idsA=explode(",",$_SESSION['sta_clipboard']);
$to_remove = array($id);
$newA=array_diff($idsA, $to_remove);
$new=implode(",",$newA);
$_SESSION['sta_clipboard']=$new;

$res->javascript("clipboard=[$new];$('#clipboard').html(clipboard.length);");
$res->javascript("$('#cs_$id').remove()");
$res->sound('beep');
?>