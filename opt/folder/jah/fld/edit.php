<?

include_once("opt/folder/folder.class.php");
	
	
	$row=new simFolder($database);
	$row->load($id);
	
	$tmpl->readTemplatesFromInput( "opt/folder/jah/edit.html");
	$tmpl->addObject("opt_folder", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_folder");
	
	$res->change('popuptitle','Folder');
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	$res->javascript("document.saveMapaForm.naziv.focus();document.saveMapaForm.naziv.select()");

?>
