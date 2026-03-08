<?
	
	$u=new simUser($database);
	$u->load($id);
	
	if ($u->super) {
		$actt=new jahAction('alert');
		$actt->addBlock("This user can not be removed. It is administrator.");
	} else { 

		$q="DELETE FROM user WHERE id=$id";
		$database->setQuery($q);
		$database->query();
		
			
		$actt=new jahAction('delete','us'.$id,'_tmplist');
		if (!$hideMSG) $actt->addBlock(_A_USER_DEL);
	}
	
	$res->addAction($actt);


?>