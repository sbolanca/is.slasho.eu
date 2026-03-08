<?

$cid=intval(simGetParam($_REQUEST,'cid',0));


	updateTable('news','galleryID',$id);
	clearTable('gallery','id',$id);
	clearTable('gallery_item','galleryID',$id);
	
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("window.location.reload( true );",'jscr');
	$res->addAction($actt);


?>