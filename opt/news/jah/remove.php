<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

$fld=trim(simGetParam($_REQUEST,'fld','published'));


	$query="UPDATE news SET $fld=0 WHERE id=$id";	
	$database->setQuery($query);
	$database->query();
	
	if ($fld=='frontpage') {
		$query="UPDATE news SET ordering_front=0 WHERE id=$id";	
		$database->setQuery($query);
		$database->query();
	}
	
	$actt=new jahAction('delete','n_'.$id);
	$actt2=new jahAction('delete','ho_'.$id);
	
	$res->addAction($actt);
	$res->addAction($actt2);


?>