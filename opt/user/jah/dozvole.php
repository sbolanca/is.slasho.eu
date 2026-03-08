<?
	$row=new simUser($database);
	$row->load($id);
	$opts=array();
	
class Per {
	var $opt=null;
	var $permission=null;
	function Per( $optx='',$perm='' ) {
			$this->opt=$optx; 
			$this->permission=$perm;
	}
	function setPermission($p) {
		if ($p) {
			$this->$p='selected';
			$this->permission=$p;
		}
	}
}		

if($row->username) {
			$dir=$simConfig_absolute_path."/opt";
			
			$d = dir($dir);
			while (false !== ($entry = $d->read())) {
				if(is_dir($dir.'/'.$entry) && substr($entry,0,1) != '.') $opts[$entry]=new Per($entry);
				
			}
			$d->close();
	
	$database->setQuery("SELECT opt,permission FROM permissions WHERE username='".$row->username."'");
	$dbp=$database->loadObjectList('opt');
	
	$usedopts=array_keys($dbp);
	foreach($opts as $k=>$v) 
		if (in_array($k,$usedopts)) 
			$opts[$k]->setPermission($dbp[$k]->permission);
	
	$tmpl->readTemplatesFromInput( "opt/user/jah/dozvole.html");
	
		
	
	$tmpl->addObject("opt_user", $row, "row_",true);
	$tmpl->addObject("opt_user_p", $opts, "row_",true);
	
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_user");

	$res->change('popuptitle',	'DOZVOLE: '.$row->name);
	$res->change('ed_content',	$cont);
	$res->javascript("showEditPopup('popupdescription');");	
} else $res->alert("Ne možete postavljati dozvole korisniku koji nema korisničko ime.");
	

?>