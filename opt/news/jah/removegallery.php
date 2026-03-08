<?




	$q="UPDATE news SET galleryID=0 WHERE id=$id";
	$database->setQuery($q);
	$database->query();
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("window.location.reload( true );",'jscr');
	$res->addAction($actt);


?>