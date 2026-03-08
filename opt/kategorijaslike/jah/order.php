<?php
include('opt/kategorijaslike/kategorijaslike.class.php');



$blank=array();
$ordering=explode('|',trim(simGetParam($_REQUEST,'ord','')));
$row=new simKategorijaSlike($database);

if(count($ordering)) {
	
	$i=1;
	$updated=array();
	foreach($ordering as $o) {
		$row->load($o);
		if(!(intval($row->ordering)==$i)) {
			$database->execQuery("UPDATE kategorijaslike SET ordering=$i WHERE id=$o");
			//$LOG->createTblLog('kategorijaslike',$o,"Korekcija rednog broja povlaèenjem",$row->ordering." -> ".$i);
			$updated[]=$o;
		}
		$i++;
	}

	if(count($updated)) {
		//$LOG->saveIlog(1,"Korekcija redoslijeda na papiru povlaèenjem","List br.".$row->list,"ID-evi pomicanih izvoðenja: ".implode(",",$updated),$row->list,false);
		$res->javascript("activateCMCommandPOST('ordering','$pageact','searchKSForm')");
	} else $res->alert("Nema izmjena redoslijeda");
} else $res->alert("Nema zapisa");
	
?>