<?




// Ucitava povezane lookup vrijednosti kao niz stavki.
function loadExpandedLookupFieldArray(&$row,&$lookup,$fld,$tbl,$key,$keyfixedFld,$lnk='',$lnktitle='',$lnkcondition='',$filterFld='id') {
		global $database;
		$cntfld='cnt_'.$fld;
		$ret=array();
		if ($lnktitle) $lnktitle='title="'.$lnktitle.'"';
		if (intval($row->$cntfld)) {
			  if (intval($row->$cntfld)==1) {
			  	if ($lnk && (!$lnkcondition || ($lnkcondition && !($row->$key==$lnkcondition)))) $ret[]='<a href="'.$lnk.$row->$key.'" '.$lnktitle.'>'.$lookup[$row->$key].'</a>';
				else $ret[]=$lookup[$row->$key];
			  } else {
				$database->setQuery("SELECT DISTINCT($key) as mid FROM $tbl WHERE $keyfixedFld=".$row->$filterFld);
				$array_m=$database->loadResultArray();
				replaceWithSifrarnik($array_m,$lookup,$lnk,$lnktitle,$lnkcondition);
				$ret=$array_m;
			  }
			}
		return $ret;
}
// Spaja povezane lookup vrijednosti u jedan tekstualni niz.
function loadExpandedLookupFieldString(&$row,&$lookup,$fld,$tbl,$key,$keyfixedFld,$lnk='',$lnktitle='',$lnkcondition='',$separator=', ',$filterFld='id') {
		$array_m=loadExpandedLookupFieldArray($row,$lookup,$fld,$tbl,$key,$keyfixedFld,$lnk,$lnktitle,$lnkcondition,$filterFld);
		$row->$fld=implode($separator,$array_m);
}


// Brise retke iz tablice prema zadanom polju i vrijednosti.
function clearTable($table,$field,$id,$cond='') {
		global $database;
		if (is_string($id) && (count(explode(',',$id))>1))
			$q="DELETE FROM $table WHERE $field IN ($id)".($cond ? " AND $cond" : '');
		else
			$q="DELETE FROM $table WHERE $field='$id'".($cond ? " AND $cond" : '');
		$database->setQuery($q);
		return $database->query();	
}
// Brise povezane retke iz glavne i dodatnih tablica.
function clearComplexTable($table,$ntable,$field,$id,$nid='id',$tid='id') {
		global $database;
		$ret=true;
		if (!(is_array($ntable))) $ntable=explode(',',$ntable);
		if (!(is_array($nid))) $nid=explode(',',$nid);
		
		if (is_string($id) && (count(explode(',',$id))>1))
			$q="SELECT DISTINCT($tid) FROM $table WHERE $field IN ($id)";
		else
			$q="SELECT DISTINCT($tid) FROM $table WHERE $field='$id'";
		$ilist=$database->loadResultArrayText($q);
		if ($ilist) {
			$q="DELETE FROM $table WHERE $tid IN ($ilist)";
			$database->setQuery($q);
			$ret=$database->query();
			
			for ($i=0;$i<count($ntable); $i++)  if (!clearTable($ntable[$i],$nid[$i],$ilist)) $ret=false;
		}				
		return $ret;
}

// Azurira vrijednost polja u tablici za zadani zapis ili skup zapisa.
function updateTable($table,$field,$id,$newval=0) {
		global $database;
		if (is_string($id) && (count(explode(',',$id))>1))
			$q="UPDATE $table SET $field='$newval' WHERE $field IN ($id)";
		else
			$q="UPDATE $table SET $field='$newval' WHERE $field='$id'";
		$database->setQuery($q);
		return $database->query();	
}

// Vraca jedinstvene vrijednosti polja kao tekstualnu listu ID-eva.
function loadRowsIdAsText($table,$field,$filterFld,$filterValue) {
	global $database;
	if (is_string($filterValue) && (count(explode(',',$filterValue))>1))
			$q="SELECT DISTINCT($field) FROM $table WHERE $filterFld IN ($filterValue) ";
	else
			$q="SELECT DISTINCT($field) FROM $table WHERE $filterFld='$filterValue' ";
	return $database->loadResultArrayText($q);		
}

// Vraca vrijednosti koje su vezane iskljucivo uz zadani filter.
function loadExactRowsIdAsText($table,$field,$filterFld,$filterValue) {
	global $database;
	if (is_string($filterValue) && (count(explode(',',$filterValue))>1))
			$q="SELECT DISTINCT($field) FROM $table WHERE $filterFld IN ($filterValue) AND $field NOT IN (SELECT DISTINCT($field) FROM $table WHERE $filterFld NOT IN (NULL,$filterValue))";
	else
			$q="SELECT DISTINCT($field) FROM $table WHERE $filterFld='$filterValue' AND $field NOT IN (SELECT DISTINCT($field) FROM $table WHERE $filterFld<>'$filterValue' AND $filterFld IS NOT NULL)";
	return $database->loadResultArrayText($q);		
}
// Rekurzivno gradi putanju nadredenih ID-eva za zapis.
function loadPathId($tbl,$id) {
	global $database;
	$tmp=array();
	if (intval($id)>0) {
		$database->setQuery("SELECT id, parentID FROM $tbl WHERE id IN ($id)");
		$par=$database->loadObjectList();
		if (count($par)>0) {
			array_push($tmp, $par[0]->id);
			if (intval($par[0]->parentID)>0) $tmp=array_merge($tmp, loadPathId($tbl,$par[0]->parentID));
		}
	}
	return $tmp;
}
// Rekurzivno dohvaca sve podredene ID-eve za zadani zapis.
function loadSubIds($tbl,$id,$putInitial=true,$idFld='id',$parentFld='parentID') {
	global $database;
	$tmp=array();
	if ($putInitial) $tmp[]=$id;
	if (intval($id)>0) {
		$database->setQuery("SELECT $idFld FROM $tbl WHERE $parentFld=$id");
		$subList=$database->loadResultArray();
		if (count($subList)>0) foreach($subList as $s){
			array_push($tmp, $s);
			$subTmp=loadSubIds($tbl,$s,false,$idFld,$parentFld);
			if (count($subTmp)) $tmp=array_merge($tmp, $subTmp);
		}
	}
	return $tmp;
}
// Pretvara svojstva objekta u jednostavan tekstualni zapis.
function exportObject( $obj,$ttl="" ) {
		
		if (trim($ttl)) $xml=$ttl."\n"; else $xml = '';
		
		foreach (get_object_vars( $obj ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL ) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			
			$xml .= $k . '='. $v . "\n";
		}
		if ($ttl) $xml.="\n";
		return $xml;
	}

// Upisuje konfiguraciju zaglavlja tablice u JavaScript varijable.
function printTableHeaderJSVars($tbl,$sort=false) {
	global $SETTINGS,$mainFrame;
	
	$ws= (isset($_SESSION[$tbl.'_widths'])) ? $_SESSION[$tbl.'_widths'] : $ws= $SETTINGS[$tbl.'_widths'];	
	$fields=explode(",",$SETTINGS[$tbl.'_fields_all']);
	$W=explode(",",$ws);
	$H=explode(",",$SETTINGS[$tbl.'_header']);
	$T=explode(",",$SETTINGS[$tbl.'_types']);
	$A=explode(",",$SETTINGS[$tbl.'_aligns']);
	if ($sort) $S=explode(",",$SETTINGS[$tbl.'_sortings']);
	for ($i=0; $i<count($fields); $i++) {
		$headers[]=$fields[$i].":'".$H[$i]."'";
		$widths[]=$fields[$i].":'".$W[$i]."'";
		$aligns[]=$fields[$i].":'".$A[$i]."'";
		$types[]=$fields[$i].":'".$T[$i]."'";
		if ($sort) $sorts[]=$fields[$i].":'".$S[$i]."'";
	}
	$mainFrame->addHeaderScript("var ".$tbl."_fields='".$_SESSION[$tbl.'_fields']."';",$tbl."_fields");
	$mainFrame->addHeaderScript("var ".$tbl."_H={".implode(",",$headers)."};",$tbl."_H");
	$mainFrame->addHeaderScript("var ".$tbl."_W={".implode(",",$widths)."};",$tbl."_W");
	$mainFrame->addHeaderScript("var ".$tbl."_A={".implode(",",$aligns)."};",$tbl."_A");
	$mainFrame->addHeaderScript("var ".$tbl."_T={".implode(",",$types)."};",$tbl."_T");
	if ($sort) $mainFrame->addHeaderScript("var ".$tbl."_S={".implode(",",$sorts)."};",$tbl."_S");
}

// Salje konfiguraciju zaglavlja tablice kroz JAH JavaScript odgovor.
function updateTableHeaderJSVarsJah($tbl,$sort=false) {
	global $SETTINGS,$res;
	
	$ws= (isset($_SESSION[$tbl.'_widths'])) ? $_SESSION[$tbl.'_widths'] : $SETTINGS[$tbl.'_widths'];	
	
	//$fields=explode(",",$_SESSION[$tbl.'_fields']);
	$fields=explode(",",$SETTINGS[$tbl.'_fields_all']);
	$W=explode(",",$ws);
	$H=explode(",",$SETTINGS[$tbl.'_header']);
	$T=explode(",",$SETTINGS[$tbl.'_types']);
	$A=explode(",",$SETTINGS[$tbl.'_aligns']);
	if ($sort) $S=explode(",",$SETTINGS[$tbl.'_sortings']);
	for ($i=0; $i<count($fields); $i++) {
		$headers[]=$fields[$i].":'".$H[$i]."'";
		$widths[]=$fields[$i].":'".$W[$i]."'";
		$aligns[]=$fields[$i].":'".$A[$i]."'";
		$types[]=$fields[$i].":'".$T[$i]."'";
		if ($sort) $sorts[]=$fields[$i].":'".$S[$i]."'";
	}
	$res->javascript($tbl."_fields='".$_SESSION[$tbl.'_fields']."';");
	$res->javascript($tbl."_H={".implode(",",$headers)."};");
	$res->javascript($tbl."_W={".implode(",",$widths)."};");
	$res->javascript($tbl."_A={".implode(",",$aligns)."};");
	$res->javascript($tbl."_T={".implode(",",$types)."};");

	if ($sort) $res->javascript($tbl."_S={".implode(",",$sorts)."};");
}


// Dodaje trazene joinove i njihove ovisnosti u listu potrebnih joinova.
function addDBRelatedJoins($joins,&$requiredArr,&$allInArr,$sep='|') {
	if (trim($joins)) foreach(explode($sep,trim($joins)) as $j) if (!in_array($j,$requiredArr)) {
		array_push($requiredArr,$j);
		addDBRelatedJoins($allInArr[$j]->required_previous_joins,$requiredArr,$allInArr);
	}
}
// Gradi SQL dio za SELECT polja prema trazenim stupcima.
function getDBFieldsQuery($flds,&$FIELDS,&$required_joins,&$JOINS) {
	$fldsSQL='';
	foreach($FIELDS as $k=>$v) 
		if (!$v->required_joins && $v->selecting) $fldsSQL.=",".$v->selecting." as ".$v->name;
		else if ($v->required_joins && in_array($k,$flds)) {
			$fldsSQL.=",".$v->selecting." as ".$v->name; 
			addDBRelatedJoins($v->required_joins,$required_joins,$JOINS);
		}
	return $fldsSQL;
}
// Gradi SQL dio za JOIN naredbe na temelju potrebnih joinova.
function getDBJoinQuery(&$required_joins,&$JOINS) {
	$joinsSQL='';
	foreach($JOINS as $k=>$v) 
		if (in_array($k,$required_joins)) 
			$joinsSQL.="\n ".$v->joining;
	return $joinsSQL;
}

// Sprema generirane SQL dijelove za stupce i joinove u sesiju.
function updateColumnsDependedQuerySession($tbl,$flds,&$FIELDS,&$required_joins,&$JOINS){
		
		$fldsSQL=getDBFieldsQuery($flds,$FIELDS,$required_joins,$JOINS);
		$joinsSQL=getDBJoinQuery($required_joins,$JOINS);
		
		$_SESSION[$tbl.'_SQLFIELDS']=$fldsSQL; 
		$_SESSION[$tbl.'_SQLJOINS']=$joinsSQL; 
		
		
	}
// Ucitava modul i umece njegov sadrzaj u predlozak.
function insertModule(&$tmpl,$ix,$t='',$var='ACCORDIAN') {
	global $database,$opt,$mainFrame;
	if (!$t) $t='opt_'.$opt;
	$module=new simModule($database);
	$module->load($ix);
	$cont=getModuleContent($tmpl,$module);
	$tmpl->addVar( $t, $var, $cont);
}
// Oznacava zapis kao pregledan od strane trenutnog korisnika.
function setViewedMark(&$row,$table='prijava',$userFld='clanID') {
	global $database,$myID;
	$isFU=property_exists(get_class($row),'forum_userID')?1:0; 
	$database->execQuery("UPDATE $table SET viewed='$myID',forum_date=NOW()".($isFU?",forum_userID='$myID'":'')." WHERE id=".$row->id);
	$row->viewed=$myID;
	return $myID;
}
// Dodaje trenutnog korisnika u listu pregledanih zapisa.
function removeViewed(&$row,$table='prijava') {
	global $database,$myID;
	addToTextArray($row->viewed,$myID,',');
	$database->execQuery("UPDATE $table SET viewed='".$row->viewed."' WHERE id=".$row->id);
}
// Kreira i inicijalizira patTemplate objekt s globalnim varijablama.
function createTemplate( $t, $isAdmin=false, $useCache=false ) {
		
		global $template,$lang,$simConfig_live_site,$lang_encoding;
		require_once 'inc/patError/patErrorManager.php';
		
		$tmpl = new patTemplate;

		// patTemplate
		if ($useCache) {
	   		$tmpl->useTemplateCache( 'File', array(
                'cacheFolder' => $GLOBALS['vnnConfig_cachepath'], 'lifetime' => 20 ));
		}

		$tmpl->setNamespace( 'sim' );

	

		//echo $this->basePath() . '/components/' . $option . '/tmpl';

		$tmpl->setRoot( 'tmpl/'.$template );
		$tmpl->applyInputFilter('ShortModifiers');
		$tmpl->addGlobalVar("MAINPAGE",$isAdmin?"admin.php":"index.php");
		$tmpl->addGlobalVar("_MORE",_MORE);
		$tmpl->addGlobalVar("ISADMIN",$isAdmin?1:0);
		$tmpl->addGlobalVar("_MOREG",_MOREG);
		$tmpl->addGlobalVar("LANG",$lang);
		$tmpl->addGlobalVar("LIVESITE",$simConfig_live_site);
		$tmpl->addGlobalVar("LANGENCODING",$lang_encoding);
		
		$iso = explode( '=', _ISO );
		$tmpl->addGlobalVar( 'page_encoding', $iso[1] );
		if($t) $tmpl->readTemplatesFromInput($t );

		return $tmpl;
}

// Vraca naslov taba komentara s brojem komentara.
function getKomentariTabTitle($id,$tbl='prijava',$idfld='prijava') {
	global $database;
	$kCnt=$database->getResult("SELECT COUNT(id) FROM ".$tbl."_forum WHERE ".$idfld."ID=".$id);
	return 'Komentari'.($kCnt?" ($kCnt)":'');
}
// Sastavlja puni naslov retka iz osnovnih polja.
function getRowFullTitle(&$row,$includeID=false,$fld='izvodjac') {
	$ret=$row->naziv;
	if (isset($row->$fld) && trim($row->$fld)) $ret.=" / ".$row->$fld;
	if (isset($row->godina) && trim($row->godina)) $ret.=" [".$row->godina."]";
	if($includeID) $ret=$row->id." | ".$ret;
	return $ret;
}
// Vraca oznaku za multiselect ili alternativni tekst.
function getMultiTitle(&$list,$alt='') {

	if(count($list)>1) return "»»» multiselect (".count($list).") «««";
	else return $alt;
}

// Popunjava predlozak podacima za kazalo foldera.
function setFolderKazalo($SN,$tmpl,$opt) {
	if(isset($SN['folder'])) {
		$colors=5;$hide=0;
		$arr=array();
		for($i=1;$i<=$colors;$i++) {
			$vname='cls'.$i;
			if(!trim($SN['folder']->$vname)) {
					$hide++;
					$tmpl->addVar("opt_".$opt."_fkazalo","hide$i",'display:none');
			} else $arr[$i]=$SN['folder']->$vname;
		}
		if($hide==$colors) $tmpl->addVar("opt_".$opt."_fkazalo","hideall",'display:none');
		$tmpl->addVar("opt_".$opt."_fkazalo","fopt","snimka");
		$tmpl->addObject("opt_".$opt."_fkazalo",$SN['folder'], "row_",true);
	}
}
// Dohvaca podatke o programu prema sifri postaje ili ID-u.
function getProgramData($postaja_code) {
	global $database;
	if((substr($postaja_code,0,1)=='1') || (substr($postaja_code,0,1)=='0'))  {
			$programID=intval($postaja_code);
			$database->getResult("SELECT p.*,e.code,e.naziv as emiter,m.naziv as emisija FROM program AS p"
		." LEFT JOIN emiter AS e ON e.id=p.emiterID"
		." LEFT JOIN emisija AS m ON m.id=p.emisijaID"
		." WHERE p.id=$programID");
		$database->loadObject($row);
	} else {
		$database->getResult("SELECT p.*,e.code,e.naziv as emiter,'' as emisija FROM program AS p"
		." LEFT JOIN emiter AS e ON e.id=p.emiterID"
		." WHERE p.emisijaID=0 AND e.code='$postaja_code'");
		$database->loadObject($row);
	}
	return $row;
}

// Inicijalizira korisnicke postavke stupaca i sirina u sesiji.
function setTblSessions($tbl) {
	global $database,$SETTINGS;
	$myID=intval($_SESSION['MM_id']);
	if (!isset($_SESSION[$tbl."_fields"])) {
		$database->setQuery("SELECT value,type FROM settings WHERE type IN ('".$tbl."_fields','".$tbl."_widths') AND userID=$myID");
		$values=$database->loadObjectList('type');
		$_SESSION[$tbl."_fields"]=isset($values[$tbl."_fields"]->value)?$values[$tbl."_fields"]->value:$SETTINGS[$tbl."_fields"];		
		if (!isset($_SESSION[$tbl."_widths"])) $_SESSION[$tbl."_widths"]=isset($values[$tbl."_widths"]->value)?$values[$tbl."_widths"]->value:$SETTINGS[$tbl."_widths"];		
	}	
	
}
// Dohvaca konfiguracijsku vrijednost po tipu.
function getConfig($type) {
	global $database;
	return $database->getResult("SELECT value FROM configuration WHERE type='$type'");
}

// Vraca mapu zaglavlja tablice prema konfiguraciji iz sesije.
function getHeaderArrayFromSessionField($tbl) {
	global $SETTINGS;
	$ret=array();
	$all=explode(",",$SETTINGS[$tbl.'_fields_all']);
	$head=explode(",",$SETTINGS[$tbl.'_header']);
	foreach($all as $i=>$a) $ret[$a]=$head[$i];
	return $ret;
}

// Ucitava povezane retke iz _link tablice zajedno s trazenim poljima.
function loadLinkedTable($tbl,$flds) {
	global $database;
	$fldslist="s.".str_replace(",",",s.",$flds);
	$database->setQuery("SELECT l.id AS old_ID,'0' as linkedCnt,l.newID as new_ID,$fldslist "
	."\n FROM ".$tbl."_link AS l LEFT JOIN $tbl AS s ON s.id=l.newID");
	return $database->loadObjectList('old_ID');
}
// Pronalazi krajnji povezani zapis i broj preskocenih veza.
function getNewLinkedRow(&$linkRows,$oldID){
	$newID=$oldID; $linked=0;
	while (isset($linkRows[$newID])) {
		$linked++;$oldID=$newID;
		$newID=$linkRows[$newID]->new_ID;
	}
	$linkRows[$oldID]->linkedCnt=$linked;
	return $linkRows[$oldID];

}


// Vraca obracunsku godinu iz requesta ili sesije i po potrebi je sprema.
function getObracunskaGodina($setSession=true){
	global $simConfig_YEAR;
	$sessObrGod=intval(simGetParam($_SESSION,'obrgod',$simConfig_YEAR));
	$obrgod=intval(simGetParam($_REQUEST,'obrgod',$sessObrGod));
	if($setSession && (!isset($_SESSION['obrgod']) || !($sessObrGod==$obrgod)))
		$_SESSION['obrgod']=$obrgod;
	return $obrgod;
}
?>
