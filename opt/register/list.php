<?


	$query="SELECT * FROM user ORDER by username";

	$database->setQuery($query);
	$items=$database->loadObjectList();
	
	$tmpl->addObject("opt_register_item", $items, "row_",true);
	
		
	

?>