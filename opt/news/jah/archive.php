<?


	
	$q="UPDATE news SET archive=1 WHERE id=$id";
	$database->setQuery($q);
	$database->query();
	
		
	$actt=new jahAction('delete','ns_'.$id,'subnews');
	$act=new jahAction('delete','n_'.$id,'contenttext');
	if (!$hideMSG) $act->addBlock("Novost je spremljena u arhivu!");
	$res->addAction($actt);
	$res->addAction($act);


?>