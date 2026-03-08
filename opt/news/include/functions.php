<?

function getFullNewsRow($id,$fields="title,content,menu",$sim=true) {
	global $database;
	$row=new simNews($database);
	$row->load($id);
	if ($sim) $row->convertVannaLinks($fields);
	$row->addField(array("fulldate"));
	$row->fulldate=simConvertSQLDateTimeToFormat($row->start_date,"d.m.Y");
	return $row;
}

function saveLangNewsRow($isJah) {
	global $database;
	$row=new simNews($database);
	$row->bind($_POST);
	$row->check($isJah);
	$row->store();
	return $row;
}

function getGroupsMenu(&$tmpl,$gid) {
	global $lang,$database,$mainFrame,$template;
	$where=array();
	if (!$mainFrame->isAdmin) $where[]='c.published=1';

	$query="SELECT c.id,c.published,c.groupID, IF(c.menu<>'' AND c.menu IS NOT NULL,c.menu,c.title) as title, '' as sub, '' as _sub FROM news as c "
	."\n WHERE c.parentID=0 AND c.groupID=".$gid.(count($where) ? " AND ".implode(" AND ",$where) : "")
	."\n ORDER BY c.ordering";
	$database->setQuery($query);
	$groups=$database->loadObjectList();
	
	for($i=0;$i<count($groups);$i++) {
		$groups[$i]->_sub=array();
		$query="SELECT c.id,c.published, c.parentID, IF(c.menu<>'' AND c.menu IS NOT NULL,c.menu,c.title) as title, '' as sub, '' as _sub FROM news as c "
		."\n WHERE c.parentID=".$groups[$i]->id.(count($where) ? " AND ".implode(" AND ",$where) : "")
		."\n ORDER BY c.ordering";
		$database->setQuery($query);
		$groups[$i]->_sub=$database->loadObjectList();
	}
	
	for ($i=0;$i<count($groups);$i++) if(count($groups[$i]->_sub)) {
		$tmpl->addObject("opt_news_subgroup_sub", $groups[$i]->_sub, "gr_",false);
		$tmpl->addVar('opt_news_subgroup', "ID", $groups[$i]->id); 
		$groups[$i]->sub=$tmpl->getParsedTemplate("opt_news_subgroup"); 
		$tmpl->clearTemplate("opt_news_subgroup_sub"); 
		$tmpl->clearTemplate("opt_news_subgroup"); 
	}
	$tmpl->addObject("opt_news_hmenu", $groups, "gr_",true); 
	
	if ($mainFrame->isAdmin && false) {
		for ($i=0;$i<count($groups);$i++) if(count($groups[$i]->_sub))
			$mainFrame->addFooterScript("Sortable.create('gm".$groups[$i]->id."',{tag:'li'});","gm".$groups[$i]->id."sortable");
	}
		$mainFrame->addCSS("tmpl/$template/opt/news/css/mm.css","newsmmcss");

	$mainFrame->addBodyAction("onLoad","startList('mm');");
	$mainFrame->includeScript("js/mm.js","mmsrcipt");
	return $groups;

}

function loadNewsTemplate(&$tmpl,$tf='') {
	if (!$tf) $tf='list';
	$tmpl->readTemplatesFromInput( "opt/news/".$tf.".html");
	return $tf;
}

function processNewsLink(&$row) {
		if ($row->subcnt || $row->parentID) 
		 $row->link="act=mlist&amp;id=".$row->id;
		else $row->link="id=".$row->id;
	}



?>