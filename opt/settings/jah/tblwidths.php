<?
	$blank=array();
	$table=trim(simGetParam($_REQUEST,'table',''));
	$widths=trim(simGetParam($_REQUEST,'widths',''));
	$widthsArr=explode(",",trim(simGetParam($_REQUEST,'widths','')));
	
	$fields=explode(",",$_SESSION[$table."_fields"]);
	$all=explode(",",$SETTINGS[$table."_fields_all"]);
	$allw=explode(",",$SETTINGS[$table."_widths"]);
	
	$arr=array();
	$i=0;
	foreach($all as $fld) {
		if (in_array($fld,$all)) $arr[]=$widthsArr[intval(array_search($fld,$fields))];
		else $arr[]=$allw[$i];
		$i++;
	}
	$newwidths=implode(",",$arr);
	if ($newwidths && (
		(!isset($_SESSION[$table."_widths"]) && !($newwidths==$SETTINGS[$table."_widths"])) ||
		(isset($_SESSION[$table."_widths"]) && !($newwidths==$_SESSION[$table."_widths"])))){
			
			$_SESSION[$table."_widths"]=$newwidths;
			$res->javascript('if (confirm("Postavke tablice su spremljene.\n'
			.'Želite li da program zapamti ove postavke kad se drugi put logirate?"))'
			.' activateCMCommand("settings","tblsave","table='.$table.'");');
	} else $res->alert('Nema promjena na tablici');
		
	
	


	

?>