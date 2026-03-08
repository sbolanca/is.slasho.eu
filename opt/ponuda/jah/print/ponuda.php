<?

$ids=trim(simGetParam($_REQUEST,'ids',''));



	$_SESSION['ponudaIDS']=$ids; 
	$cnt=count(explode("|",$ids));
	$arr=array();
	foreach($selectedActions as $sel) $arr[]="$sel=1&";
	


$res->javascript("new_tab('rplc2.php?".implode("",$arr)."scr=opt/ponuda/print-ponuda')");




?>
