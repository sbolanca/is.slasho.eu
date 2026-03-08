<?
    $pth=array_reverse(loadPathId('sta_folder',$id));
	$res->javascript("openPath('".implode(",",$pth)."');");

?>
