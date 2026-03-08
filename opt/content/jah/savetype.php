<?



include_once("opt/content/content.class.php");

	$row=new simContent($database);
	$row->bind($_POST);
	$row->store();
	
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("location.href='index.php?opt=content&id=".$row->id."';",'jscr');
	$res->addAction($actt);


?>