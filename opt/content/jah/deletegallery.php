<?

	updateTable('content','galleryID',$id);
	clearTable('gallery','id',$id);
	
	clearTable('gallery_item','galleryID',$id);

		
	$actt=new jahAction('change','editbox');
	$actt->addBlock("location.href='index.php?opt=content&id=$cid';",'jscr');
	$res->addAction($actt);


?>