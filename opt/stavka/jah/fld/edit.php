<?

include_once("opt/stavka/folder.class.php");
	
	
	$row=new simStaFolder($database);
	$row->load($id);
	
	$tmpl->readTemplatesFromInput( "opt/folder/jah/fld/edit.html");
	$tmpl->addVar("opt_folder","fopt","stavka");
	$tmpl->addObject("opt_folder", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_folder");
	
	$res->change('popuptitle','Folder: '.$id);
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	$res->javascript("document.saveFolderForm.naziv.focus();document.saveFolderForm.naziv.select()");

?>
