<?

if (!$row->galleryID && !count($galleryRows) && trim($row->type)) $tf='mlistg';
else $tf='';


$group=new simGroup($database);
$group->load($row->groupID,'title,description');
$pathArr=array();
$path=array_reverse(loadPathId('news',$row->parentID));

if (count($path)) {
	$pthstr=implode(',',$path);
	$database->setQuery("SELECT id, title  FROM news WHERE id IN ($pthstr)  ORDER BY FIELD(id,$pthstr)");
	$pathArr=$database->loadObjectList();
}

getGroupsMenu($tmpl,$group->id);

$tmpl->addVar("main", "HEADING2", $group->title); 

		$q="SELECT c.*,DATE_FORMAT(c.start_date,'%W, %e. %M %Y') as fulldate "
		.(($mainFrame->isAdmin) ? ", ".makeSqlCM(true,"CM_opt_news_intro","c.id","c.parentID","'\'".$tf."\''").", ".makeSqlTagID("n_","c.id") :"")
		."\n FROM news as c"
		."\n WHERE c.parentID=$id AND c.archive=0"
		.($mainFrame->isAdmin ? "" : "\n AND c.published=1")
		."\n ORDER BY c.ordering";
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		for($i=0;$i<count($rows);$i++) {
			$rows[$i]->fulldate=simDateTimeToLocal($rows[$i]->fulldate);
			if (!$rows[$i]->image) $rows[$i]->image='blank.jpg';
			else  setThumbScript($rows[$i]);
			//$rows[$i]->more=constant('_MORE'.(!$rows[$i]->galleryID ? strtoupper($rows[$i]->type) : ''));
			$rows[$i]->more=_MORE;
		}
setOrderPos($rows);	

$tmpl->addObject("opt_news_contentlist", $rows, "row_",true); 
$tmpl->addObject("opt_news_content", $row, "row_",true); 
$tmpl->addVar("opt_news_contentlist",'count', count($rows)); 
if (!count($rows) && $mainFrame->isAdmin) $tmpl->addCMVar( "opt_news_gallery", "NEWS_EMPTY", 'CM_opt_newssub',0);
$tmpl->addVar("opt_news_contentlist",'_MORE', _MORE); 
$tmpl->addVar("opt_news", "id", $id); 
$tmpl->addObject("opt_news_path", $pathArr, "path_",true); 
$tmpl->addObject("opt_news", $group, "GR_",true); 

if (count($galleryRows)) {
	$tmpl->addObject("opt_news_gallery_item", $galleryRows, "grow_",true); 
	$tmpl->addVar("opt_news_gallery",'type', $row->type); 
	$tmpl->addVar("opt_news_gallery_item",'type', $row->type);
} else if  ($mainFrame->isAdmin) 
	 $tmpl->addCMVar( "opt_news_gallery", "GALLERY_EMPTY", 'CM_opt_newssub',0);
$tmpl->addVar("opt_news_gallery",'count', count($galleryRows)); 
 $mainFrame->includeScript("js/popupimg.js","popupimg");


if ($mainFrame->isAdmin) {
	$mainFrame->addHeaderScript("var groupID=$group->id;","groupID");
	$mainFrame->addHeaderScript("var showOrdering=false;","showordering");
	//$mainFrame->addFooterScript("Sortable.create('contentlist',{tag:'div'});","newssortable");
	$mainFrame->addHeaderScript("var galleryID=$row->galleryID;","galleryID");
}
?>