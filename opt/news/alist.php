<?


$tmpl->addVar("main", "HEADING2", "Aktualno"); 

		$q="SELECT c.*,'' as link, COUNT(DISTINCT(s.id)) as subcnt,DATE_FORMAT(c.start_date,'%W, %e. %M %Y') as fulldate "
		.(($mainFrame->isAdmin) ? ", ".makeSqlCM(true,"CM_opt_news_act","c.id").", ".makeSqlTagID("n_","c.id") :"")
		."\n FROM news as c"
		."\n LEFT JOIN news AS s ON s.parentID=c.id" 
		."\n WHERE c.listing=1 AND c.archive=0"
		.($mainFrame->isAdmin ? "" : "\n AND c.published=1")
		."\n GROUP BY c.id ORDER BY c.start_date DESC, c.ordering";
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		for($i=0;$i<count($rows);$i++) {
			$rows[$i]->fulldate=simDateTimeToLocal($rows[$i]->fulldate);
			if (!$rows[$i]->image) $rows[$i]->image='blank.jpg';
			else  setThumbScript($rows[$i]);
			processNewsLink($rows[$i]);
		}

$tmpl->addObject("opt_news_contentlist", $rows, "row_",true); 
$tmpl->addVar("opt_news_contentlist",'count', count($rows)); 
$tmpl->addVar("opt_news_contentlist",'_MORE', _MORE); 
$tmpl->addVar("opt_news", "id", $id); 

 $mainFrame->includeScript("js/popupimg.js","popupimg");

if ($mainFrame->isAdmin) {
	$mainFrame->addHeaderScript("var groupID=$id;","groupID");
	//$mainFrame->addFooterScript("Sortable.create('contentlist',{tag:'div'});","newssortable");
	$mainFrame->addHeaderScript("var showOrdering=false;","showordering");
}
?>