<?

$isJah=false;

include_once("opt/news/news.class.php");
include_once("opt/news/include/functions.php");

if (!($id) && !($act=="archive")) $act='list';

$month=intval(simGetParam($_REQUEST,"month",0));
$year=intval(simGetParam($_REQUEST,"year",0));

$task=$act;

global $lang_month;




$mainFrame->includeScript("js/calendar/calendar.js","calendar");
$mainFrame->includeScript("js/calendar/lang/hr.js","calendarlang");
$mainFrame->addCSS("js/calendar/calendar-sim.css","calendarcss");


switch ($act) {
	case 'show':
		$task='show';
		$row=getFullNewsRow($id,"title,content,description,keywords,menu");
		$galleryRows=getGalleryItems($row->galleryID);
		if (count($galleryRows)==0) $tmplfile='simple'.$act;
		else $tmplfile=$act;
		
		break;
	case 'mlist':
		$row=getFullNewsRow($id,"title,content,description,keywords,menu");
		$galleryRows=getGalleryItems($row->galleryID);
		if (count($galleryRows)==0 && trim($row->type)) $tmplfile='mlist'.$row->type;
		else $tmplfile='mlist';
		
		break;
		
	case 'list': case 'blog': 
		$group=new simGroup($database);
		$group->load($id);
		$tmplfile=$act.$group->type;
		
		break;
	case 'editmeta':
		$task='show';
		$row=getFullNewsRow($id,"title,description,keywords,menu");
		$galleryRows=array();
		$tmplfile='editmeta';
		break;
	
	
	case 'archive': if (!$year && !$month) $task='archive0';
					else $task='archive'; break;
	
	case 'mshow':
	case 'alist':
		break;
	default : $task='show';
}


$mainFrame->includeScript("js/popupimg.js","popupimg");



?>