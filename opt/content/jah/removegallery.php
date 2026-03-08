<?




	$q="UPDATE content SET galleryID=0 WHERE id=$id";
	$database->setQuery($q);
	$database->query();
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("location.href='index.php?opt=content&id=$id';",'jscr');
	$res->addAction($actt);


?>