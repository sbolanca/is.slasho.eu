<?
	$u=new simUser($database);
	$u->load($id);
	if (!$u->super) {
		$u->active=1-$u->active;
		$u->store();
		$act=new jahAction('change','ed_content');
		$act->addBlock("document.getElementById('uim".$u->id."').src='images/act".$u->active.".gif';",'jscr');
	
	} else {
		$act=new jahAction('alert');
		$act->addBlock(_A_USER_ACTWARN);
		
	}
	
	$res->addAction($act);


?>