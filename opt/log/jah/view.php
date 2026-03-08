<?
	/*
	$row=new simLog($database);
	$row->actionpath='';
	$row->load($id);
	*/
	$database->setQuery("SELECT * FROM log WHERE id=$id");
	$database->loadObject($row);
	//print_r($row);
	if (intval($row->parentID)) $row->parentID="<button onClick=\"aCMC(".$row->parentID.",'log','view')\">Login podaci</button>";
	else $row->parentID='';
	
	$row->details=nl2br($row->details);
	
	$database->setQuery("SELECT * FROM log_table WHERE logID=$id AND subjectID>0");
	$rows=$database->loadObjectList(); 
	
	
	$tmpl->readTemplatesFromInput( "opt/log/jah/view.html");
	
		
	$tmpl->addObject("opt_log_l", $rows, "row_",true);
	$tmpl->addObject("opt_log", $row, "row_",true);
	
	$cont= $tmpl->getParsedTemplate("opt_log");


	
	$res->openSimpleDialog("LOG",$cont,805,5,'white');
	

?>