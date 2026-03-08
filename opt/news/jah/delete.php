<?

	clearTable('news','id',$id);
	
	
	clearComplexTable('news','news','parentID',$id); 
	
		
			
	$actt=new jahAction('delete','ns_'.$id,'subnews');
	if (!$hideMSG) $actt->addBlock(_A_NEWS_DEL);
	$act=new jahAction('delete','n_'.$id,'contenttext');
	$res->addAction($actt);
	$res->addAction($act);


?>