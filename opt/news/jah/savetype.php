<?



include_once("opt/news/news.class.php");
$class=trim(simGetParam($_REQUEST,'class','News'));

	$cln='sim'.$class;
	$row=new $cln($database);
	
	$row->bind($_POST);
	$row->store();
	
	
	$actt=new jahAction('change','editbox');
	$actt->addBlock("window.location.reload( true );",'jscr');
	$res->addAction($actt);


?>