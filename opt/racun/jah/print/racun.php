<?

$ids=trim(simGetParam($_REQUEST,'ids',''));



	$_SESSION['racunIDS']=$ids; 
	$cnt=count(explode("|",$ids));

	$printPotpis=$selectedAction;

$res->javascript("new_tab('rplc2.php?printPotpis=$printPotpis&scr=opt/racun/print-racun')");




?>
