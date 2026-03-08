<?
$group=new simGroup($database);
$group->loadLang($lang,$id,'title,description');

$where=array();
		$where[]="c.groupID=".$id;
		if (!$mainFrame->isAdmin) $where[]="c.published=1";
		
		$where[]="YEAR(c.start_date)=$year";
		if ($month) $where[]="MONTH(c.start_date)=$month";
		
		$datetitle=$year;
		if ($month) $datetitle=$lang_month[intval($month)-1]." ".$datetitle;
		
		$q="SELECT c.*, COUNT(DISTINCT(n.id)) as subcnt, DATE_FORMAT(c.start_date,'%W, %e. %M %Y') as fulldate, DATE_FORMAT(c.start_date,'%d.%m.%Y') AS numdate FROM news as c"
		."\n LEFT JOIN news AS n ON (n.parentID=c.id)"
		.(count($where) ? "\n WHERE ".implode(" AND ",$where) : "")
		."\n GROUP BY c.id "
		."\n ORDER BY c.start_date DESC,c.ordering";
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		
		for($i=0;$i<count($rows);$i++) {
			$rows[$i]->fulldate=simDateTimeToLocal($rows[$i]->fulldate);
			processNewsLink($rows[$i]);
		}
		

$tmpl->addObject("opt_news_content", $rows, "row_",true); 
$tmpl->addVar("opt_news_content",'_MORE', _MORE); 
$tmpl->addVar("opt_news", "_NEWS_ARCHIVE", _NEWS_ARCHIVE.": ".$datetitle); 
$tmpl->addVar("opt_news", "TITLE", $group->title); 
$tmpl->addVar("main", "HEADING2", _NEWS_ARCHIVE.": ".$group->title); 


?>