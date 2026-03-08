<?



include_once("opt/news/news.class.php");

$cid=intval(simGetParam($_REQUEST,'contentid',0));
	$row=new simGallery($database);
	$row->published=1;
	$row->store();	
	
	
	
	$rowim=new simGalleryItem($database);
	$rowim->galleryID=$row->id;
	$rowim->published=1;
	$rowim->ordering=1;
	$rowim->image="blank.jpg";
	$rowim->store();	
		
	$q="UPDATE news SET galleryID=".$row->id." WHERE id=".$cid;
	$database->setQuery($q);
	$database->query();
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("location.href='index.php?opt=news&id=$cid';",'jscr');
	
	$res->addAction($actt);


?>