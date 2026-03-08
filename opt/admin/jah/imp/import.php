<?


	
	$type=trim(simGetParam($_REQUEST,'type','type'));
	
	$tmpl->readTemplatesFromInput( "opt/admin/jah/upload.html");
	$tmpl->addVar("opt_admin", 'type', $type);
	$cont= $tmpl->getParsedTemplate("opt_admin");
	
	$res->change('popuptitle','Import datoteke');
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	$res->javascript("initFileUpload('uploadFileForm','Importiraj');");
	

?>
