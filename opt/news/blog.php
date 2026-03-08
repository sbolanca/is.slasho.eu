<?



getGroupsMenu($tmpl,$group->id);

$tmpl->addVar("main", "HEADING2", $group->title); 

		$q="SELECT c.*,IF(c.menu<>'' AND c.menu IS NOT NULL,c.menu,c.title) as title,DATE_FORMAT(c.start_date,'%W, %e. %M %Y') as fulldate "
		.(($mainFrame->isAdmin) ? ", ".makeSqlCM(true,"CM_opt_news_intro","c.id","'\'\''","'\'\''").", ".makeSqlTagID("n_","c.id") :"")
		."\n FROM news as c"
		."\n WHERE c.groupID=$id AND c.parentID=0 AND c.archive=0"
		.($mainFrame->isAdmin ? "" : "\n AND c.published=1")
		."\n ORDER BY c.ordering";
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		for($i=0;$i<count($rows);$i++) {
			//if ($rows[$i]->frontpage)	$rows[$i]->fulldate=simDateTimeToLocal($rows[$i]->fulldate).(trim($rows[$i]->menu) ? ": " : "");
			
			if (!$rows[$i]->image) $rows[$i]->image='blank.jpg';
			else  setThumbScript($rows[$i]);
			$rows[$i]->more=_MORE;
		}
setOrderPos($rows);	
$tmpl->addObject("opt_news_content", $group, "row_",true); 
$tmpl->addObject("opt_news", $group, "row_",true); 
$tmpl->addVar("opt_news",'_NEWS_ARCHIVE', _NEWS_ARCHIVE); 
$tmpl->addObject("opt_news_contentlist", $rows, "row_",true); 
$tmpl->addVar("opt_news_contentlist",'count', count($rows)); 
$tmpl->addVar("opt_news_contentlist",'_MORE', _MORE); 
$tmpl->addVar("opt_news", "id", $id); 
if ($mainFrame->isAdmin) $tmpl->addCMVar( "opt_news", "CM_GROUP", 'CM_opt_newsgroup',$id);
if (!count($rows) && $mainFrame->isAdmin) $tmpl->addCMVar( "opt_news_contentlist", "NEWS_EMPTY", 'CM_opt_newssub',0);

 


if ($mainFrame->isAdmin) {
	$mainFrame->addHeaderScript("var groupID=$id;","groupID");
	//$mainFrame->addFooterScript("Sortable.create('contentlist',{tag:'div'});","newssortable");
	$mainFrame->addHeaderScript("var showOrdering=false;","showordering");
}
?>