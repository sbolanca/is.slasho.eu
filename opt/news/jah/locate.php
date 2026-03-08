<?



include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");



	$query="SELECT c.id,c.parentID,c.groupID,'' as link, COUNT(DISTINCT(s.id)) as subcnt FROM news as c"
	."\n LEFT JOIN news AS s ON s.parentID=c.id" 
	."\n WHERE c.id=$id"	
	."\n GROUP BY c.id";	
	$database->setQuery($query);
	$database->loadObject($row);
	
	processNewsLink($row);
	
		
	$actt=new jahAction('change','n_'.$id);
	//$actt->addBlock($query);
	$actt->addBlock("location.href='index.php?opt=news&amp;".$row->link."&lang=$lang';",'jscr');
	$res->addAction($actt);


?>