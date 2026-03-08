<?

include_once("opt/stavka/folder.class.php");


	$row=new simStaFolder($database);
	$row->load($id);
		
		$q="SELECT c.id,c.name,IF(c.id='".$row->userID."','checked','') as sel FROM user AS c"
			."\n WHERE c.active=1 AND (c.admin>0)"
			."\n ORDER BY c.super DESC,c.name";
		$database->setQuery($q);
		$rows=$database->loadObjectList('id');

		$tmpl->readTemplatesFromInput( "opt/folder/jah/fld/owner.html");
		$tmpl->addVar("opt_folder","fopt","stavka");
		$tmpl->addObject("opt_folder_item", $rows, "row_",true);
		$tmpl->addObject("opt_folder", $row, "row_",true);
		
		$cont= $tmpl->getParsedTemplate("opt_folder");
		$res->change('popuptitle2','Vlasnik foldera:');
		$res->change('ed_content2',$cont);
		$res->javascript("showEdit2Popup('popupdescription');");


?>
