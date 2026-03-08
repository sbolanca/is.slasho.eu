<?

include("opt/stavka/stavka.class.php");
$ids=trim(simGetParam($_REQUEST,'ids',''));
$stopa_pdv=trim(simGetParam($_REQUEST,'stopa_pdv',''));
$stopa_pdv=str_replace(",",".",$stopa_pdv);
if(!trim($stopa_pdv)) $stopa_pdv=getConfig('PDV');

	$database->setQuery("SELECT * FROM stavka WHERE id IN ($ids) AND stopa_pdv<>$stopa_pdv");
	$rows=$database->loadObjectList('id');
	
	$database->execQuery("UPDATE stavka SET stopa_pdv=$stopa_pdv WHERE id IN ($ids)");
	
	foreach($rows as $ix=>$r) 	
		$res->changeCellValueByCode('tbl_stavka',$ix,'stopa_pdv',$stopa_pdv);
	
	$res->closeSimpleDialog(2);
	

?>