<?

include_once("opt/stavka/folder.class.php");
	
	
	$row=new simStaFolder($database);
	$row->load($id);
	
	$tmpl->readTemplatesFromInput( "opt/folder/jah/fld/kazalo.html");
	for($i=1;$i<6;$i++) {
			$vname='cls'.$i;
			if(!trim($row->$vname)) $tmpl->addVar("opt_folder","hide$i",'display:none');
	}
	$tmpl->addVar("opt_folder","fopt","stavka");
	$tmpl->addObject("opt_folder", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_folder");
	
	$res->change('popuptitle','Folder: '.$id);
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	
?>
