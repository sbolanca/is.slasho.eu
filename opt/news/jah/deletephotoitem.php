<?



include_once("opt/news/news.class.php");

		
	clearTable('gallery_item','id',$id);
		
	$act=new jahAction('delete','gi_'.$id);
	if (!$hideMSG) $act->addBlock(_A_NEWS_IMGDEL);
	
	$res->addAction($act);


?>