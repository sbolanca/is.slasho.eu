<?



$mainFrame->addHeaderScript("var cid=".$row->id.";","cid");

$tmpl->addVar("opt_content",'type', $row->type); 

$tmpl->addObject("opt_content_content", $row, "row_",true); 
if ($mainFrame->isAdmin) {
	 $tmpl->addCMVar( "opt_content", "CM_CONTENTTEXT", 'CM_opt_content',$id);
	 $tmpl->addCMVar( "opt_content_content", "CM_CONTENTTEXT", 'CM_opt_content',$id);
}

?>