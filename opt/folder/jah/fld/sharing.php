<?

include_once("opt/folder/folder.class.php");
$blank=array();	
$visibility=implode(",",simGetParam($_POST,'vARR',$blank));
$selectedAction=intval(simGetParam($_POST,'sharing',$selectedAction));
$dataSaved=intval(simGetParam($_POST,'dataSaved',0));
	$row=new simFolder($database);
	$row->load($id);
	if (($selectedAction<3) || ($selectedAction>4) || $dataSaved) {
		$database->execQuery("UPDATE folder SET sharing=$selectedAction,visibility='$visibility' WHERE id=$id");	
		switch ($selectedAction) {
			case 0: $aCol="#000000"; $sCol="#ffffff"; break;
			case 5: $aCol="#0000bb"; $sCol="#bbbb00";  
			default: $aCol="#008800"; $sCol="#bbffbb";  
		}
		$res->rowCM('Ftree',$id,$row->parentID.",".$myID.",".$selectedAction);
   		$res->setNodeColor('Ftree',$id,$aCol,$sCol);
		$res->javascript("hideStandardPopup('editbox2');");
		switch($selectedAction) {
			case 0: $v="nitko"; break;
			case 4: $v="odabir osoba"; break;
			case 5: $v="SVI"; break;
			default: $v="nepoznato";
		}
		$LOG->savelog("Vidljivost foldera",$row->naziv." -> ".$v,$visibility);
	} else {
		if ((intval($row->sharing)==$selectedAction) && trim($row->visibility)) { 
			$wh="AND c.id NOT IN (".trim($row->visibility).")";
		} else 	{
			$ch="''"; $wh='';
		}
		$rows0=array();
		$q="SELECT c.id,c.name FROM user AS c"
			."\n WHERE c.active=1  $wh"
			."\n ORDER BY c.name";
		$database->setQuery($q);
		$rows=$database->loadObjectList();
		if ($wh) {
			$database->setQuery(str_replace("c.id NOT IN ","c.id IN ",$q));
			$rows0=$database->loadObjectList();
		}
		$tmpl->readTemplatesFromInput( "opt/folder/jah/visibility.html");
		$tmpl->addObject("opt_folder_item", $rows, "row_",true);
		$tmpl->addObject("opt_folder_item0", $rows0, "row_",true);
		$tmpl->addVar("opt_folder",'id',$id);
		$tmpl->addVar("opt_folder",'sharing',$selectedAction);
		$cont= $tmpl->getParsedTemplate("opt_folder");
		$res->change('popuptitle2','Vidljivost mape:');
		$res->change('ed_content2',$cont);
		$res->javascript("showEdit2Popup('popupdescription');");
	}

?>
