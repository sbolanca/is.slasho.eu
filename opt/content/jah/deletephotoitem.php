<?



include_once("opt/content/content.class.php");

	clearTable('gallery_item','id',$id);
		
	$act=new jahAction('delete','gi_'.$id);
	$act->addBlock(_A_CONT_IMGDEL);
	
	$res->addAction($act);


?>