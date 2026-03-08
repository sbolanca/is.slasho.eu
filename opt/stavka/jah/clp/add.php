<?

$blank=array();
$ids=trim(simGetParam($_REQUEST,'ids',$id));
$idsA=explode("|",$ids);

$spr=trim(simGetParam($_SESSION,'sta_clipboard',''));
$sprA=$spr?explode(",",$spr):$blank;

$newA=array_unique(array_merge($sprA,$idsA));

$new=implode(",",$newA);
$_SESSION['sta_clipboard']=$new;


$res->javascript("clipboard=[$new];$('#clipboard').html(clipboard.length);");

$res->sound('select');

$database->setQuery("SELECT * FROM stavka WHERE id IN ($new)");
$rows=$database->loadObjectList();
$list=array(); $nRows=array();
foreach($rows as $row) {
	$list[]=$row->id."| ".$row->naziv;
	if(in_array($row->id,$idsA)) {
		$nRows[]=$row;
		$LOG->createTblLog('stavka',$row->id,"Stavka u spremnik",'');
	}
}
$LOG->saveIlog(0,"Nadopunjavanje spremnika stavki",getMultiTitle($nRows,$nRows[0]->naziv),"NOVO:".($ids)."\n\n".implode("\n",$list),$id,false);


?>