<?


			$group=new simGroup($database);
			$group->load($row->groupID);
			

if (count($galleryRows)) {
	$tmpl->addObject("opt_news_gallery_item", $galleryRows, "grow_",true); 
	$tmpl->addVar("opt_news_gallery",'type', $row->type); 
	$tmpl->addVar("opt_news_gallery_item",'type', $row->type); 
}

$tmpl->addObject("opt_news_content", $row, "row_",true); 
$tmpl->addVar("opt_news", "TYPE",  $row->type); 
$tmpl->addVar("opt_news", "id", $id); 
$tmpl->addVar("main", "HEADING3", $row->fulldate);
$tmpl->addVar("main", "HEADING2", $group->title.($row->archive ? " ["._NEWS_ARCHIVE."]" : ''));


$mainFrame->addHeaderScript("var cid=".$row->id.";","cid");



if ($mainFrame->isAdmin)
	 $tmpl->addCMVar( "opt_news", "CM_CONTENTTEXT", 'CM_opt_news',$id);
	 $tmpl->addCMVar( "opt_news_content", "CM_CONTENTTEXT", 'CM_opt_news',$id);


	$mainFrame->addHeaderScript("var galleryID=$row->galleryID;","galleryID");
	if ($mainFrame->isAdmin && $row->galleryID) 
		$mainFrame->addFooterScript("Sortable.create('gallery',{tag:'div'".(($row->type=="g") ? ',constraint:false':'')."});","gallerysortable");

$mainFrame->addHeaderScript("var showOrdering=false;","showordering");

?>