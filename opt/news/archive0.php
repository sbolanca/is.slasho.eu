<?

$group=new simGroup($database);
$group->loadLang($lang,$id,'title,description');

$where=array();
		$where[]="c.groupID=".$id;
		if (!$mainFrame->isAdmin) $where[]="c.published=1";
		
		$q="SELECT '' as n , '' as month, MONTH (c.start_date) as mon,  YEAR(c.start_date) as year, DATE_FORMAT(c.start_date,'%Y%m') as ym FROM news as c"
		.(count($where) ? "\n WHERE ".implode(" AND ",$where) : "")
		."\n GROUP BY ym"
		."\n ORDER BY c.start_date DESC,c.ordering";
				
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		$y=0;
		for($i=0;$i<count($rows);$i++) {
			$rows[$i]->month=$lang_month[intval($rows[$i]->mon)-1];
				
			if ($rows[$i]->year<>$y) { 
				$rows[$i]->n=1; 
				$y=$rows[$i]->year;
				}
		}




$tmpl->addObject("opt_news_content", $rows, "row_",true); 
$tmpl->addVar("opt_news_content",'_MORE', _MORE); 
$tmpl->addVar("opt_news_content",'ID', $group->id); 
$tmpl->addVar("opt_news", "_NEWS_ARCHIVE", _NEWS_ARCHIVE); 
$tmpl->addVar("opt_news", "TITLE", $group->title); 
$tmpl->addVar("main", "HEADING2", _NEWS_ARCHIVE.": ".$group->title); 


?>