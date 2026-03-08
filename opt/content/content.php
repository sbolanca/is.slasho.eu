<?

$isJah=false;

include_once("opt/content/content.class.php");
include_once("opt/content/include/functions.php");

$task=$act;





switch ($act) {
	case 'show': 
		$task='show';
		$row=getFullContentRow($id,"title,content,description,keywords,menu");

		
		break;
	
	default : $task='show';
}
if ($row->parentID) {
	$rowp=new simContent($database);
	$rowp->load($row->parentID);
	$tmpl->addVar("main", "HEADING2", $rowp->title); 
} else if ($row->menu) $tmpl->addVar("main", "HEADING2", $row->menu); 
else $tmpl->addVar("main", "HEADING2", $row->title); 

$mainFrame->addHeaderScript("var oFCKeditor=null;","ofckinstance");
$mainFrame->addHeaderScript("var galleryID=$row->galleryID;","galleryID");


global $simConfig_sitename;

$mainFrame->setTitle($simConfig_sitename.": ".$row->title);

?>