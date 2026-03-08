<?
$table=trim(simGetParam($_REQUEST,'table',''));

	$fields=explode(",",$_SESSION[$table."_fields"]);
	$all=explode(",",$SETTINGS[$table."_fields_all"]);
	$headers=explode(",",$SETTINGS[$table."_header"]);
	
	for($i=0;$i<count($all);$i++) 
		$fieldPrint[]='<tr><td align="right"><input type="checkbox" name="fields[]" id="chk'.$all[$i]
			.'" value="'.$all[$i].'" '.(in_array($all[$i],$fields) ? 'checked' : '').' /></td>
			<td><label for="chk'.$all[$i].'">'.$headers[$i].'</label></td></tr>';
 
	
	$tmpl->readTemplatesFromInput( "opt/settings/jah/tblcols.html");
	
	$tmpl->addVar( "opt_settings", 'table',$table);
	$tmpl->addVar( "opt_settings", 'FIELDPRINT',implode("\n",$fieldPrint));
	
	
	foreach (explode(",",$_SESSION[$table."_fields"]) as $fld) $tmpl->addVar( "opt_settings_tbl", 'CHK'.$fld,'checked');
	$cont= $tmpl->getParsedTemplate("opt_settings");
	
	
	$res->change('popuptitle',	'Prikaz stupaca');
	$res->change('ed_content',	$cont);	
	$res->javascript("showEditPopup('popupdescription');");	


	
?>
