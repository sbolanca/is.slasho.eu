<?


	clearTable('content','id',$id);
	clearTable('content_lang','id',$id);
	updateTable('content','parentID',$id);
	
	
		
	$actt=new jahAction('delete','sub_'.$id,'submenues');
	if (!$hideMSG) $actt->addBlock(_A_CONT_DEL);
	$act=new jahAction('change','contenttext');
	$act->addBlock(_A_CONT_DEL);
	$res->addAction($actt);
	$res->addAction($act);


?>