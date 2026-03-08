<?

//$galleryID=intval(simGetParam($_REQUEST,'galleryID',0));

$blank=array();
$ordering=simGetParam($_POST,'gallery',$blank);
$i=1;
foreach($ordering as $o) {
	$database->setQuery("UPDATE gallery_item SET ordering=$i WHERE id=$o");
	$database->query();
	$i++;
}
	$actCont=new jahAction();
	$actCont->addBlock(_A_NEWS_IMG_ORD);
	$res->addAction($actCont);
	
	?>