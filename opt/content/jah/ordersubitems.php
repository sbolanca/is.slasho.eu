<?

//$galleryID=intval(simGetParam($_REQUEST,'galleryID',0));

$blank=array();
$ordering=simGetParam($_POST,'submenues',$blank);
$i=1;
foreach($ordering as $o) {
	$database->setQuery("UPDATE content SET ordering=$i WHERE id=$o");
	$database->query();
	$i++;
}
	$actCont=new jahAction();
	$actCont->addBlock(_A_CONT_SUB_ORD);
	$res->addAction($actCont);
	
	?>