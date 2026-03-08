<?php



class ConfigElem {
		var $mainID;
		var $default;
		var $novalue;
		var $name;
		var $label;
		var $value;
		var $size;
		var $description;
		var $type;
		var $content;
		var $sub;
		var $_wlevel;
		var $_tmpl;
		var $_param;
		var $_tmplname;
		var $_number;
		function ConfigElem (&$t,&$p,&$v,$mid,$w='') {
			$this->mainID=$mid;
			$this->_tmpl=$t;
			$this->_param=$p;
			$this->_wlevel=$w;
			$this->default = $this->_param->getAttribute( 'default' );
			$this->novalue = $this->_param->getAttribute( 'novalue' );
			$this->name = $this->_param->getAttribute( 'name' );
			$this->type = $this->_param->getAttribute( 'type' );
			$this->label = $this->_param->getAttribute( 'label' );
			$this->description = $this->_param->getAttribute( 'description' );
			$this->size=$this->_param->getAttribute( 'size' );
			$this->_number=$this->_param->getAttribute( 'number' );
			$this->value = isset($v[$this->name]) ? $v[$this->name] : $this->default;
		}
		function createFormElement($wlevel) {
			global $simConfig_template,$simConfig_absolute_path;
			$this->_tmplname="conf_".$this->type;
			$this->_wlevel=$wlevel;
			$_methods = get_class_methods( get_class( $this ) );
			if (file_exists($simConfig_absolute_path."/tmpl/".$simConfig_template."/conf/elements/".$this->type.".html")) 
				$this->_tmpl->readTemplatesFromInput( "conf/elements/".$this->type.".html");
			else $this->_tmpl->readTemplatesFromInput( "conf/elements/select.html");
			if (in_array( '_exectmpl_' . $this->type, $_methods )) {
				$this->content = call_user_func( array( &$this, '_exectmpl_' . $this->type ) );
			} 
		}
		function createVarLine($varname,$lntype="php",$ignoreEmpty=false) {
			$this->value=$_POST[$this->name];
			$this->value=str_replace("%$#","&",$this->value);
			$this->value=str_replace("*7*8*7*6*","+",$this->value);
			if ($this->_number && !($this->_number=="false") && !($this->_number="0")) $quote='';
			else {
				$quote='"';
				if(!is_array($this->value)) $this->value=trim($this->value);
				else foreach($this->value as $k=>$v) $this->value[$k]=trim($v);
			}
			$ignore=false;
			switch ($this->type) {
				case "text": case  "radio" : case "select" :
					if (trim($this->value)==$this->novalue) $ignore=true;
					break;
				case "newstype" :
					if (intval($this->value)==-1) $ignore=true;
					break;
				case "hierarchy":					
					if(!intval($this->value)) $ignore=true;
					break;
				case "multihierarchy":	case "simplequery": 				
					if(!trim($this->value)) $ignore=true;
					break;
				case "list": 
					$pomarr=explode("\n",$this->value);
					for($i=0;$i<count($pomarr);$i++) $pomarr[$i]=trim($pomarr[$i]);
					$this->value=implode("|",$pomarr);
					$this->value=str_replace("||","|",$this->value);
					break;
				case "checkbox": 
					$this->value=(isset($_POST[$this->name]) && is_array($_POST[$this->name])) ? implode("|",$_POST[$this->name]) : '';
					break;
				case "gallery":
					$quote='';
					if (!$this->value) {
						global $database;
						$q="INSERT INTO gallery (published) VALUES(1)";
						$database->setQuery($q);
						$database->query(); 
						$gid=$database->insertid();
						$q="INSERT INTO gallery_lang (id,langID,title) VALUES($gid,1,'Nova galerija $gid')";
						$database->setQuery($q);
						$database->query(); 
						$this->value=$gid;
					}
					break;
			}
			if (!$ignore) switch ($lntype)  {
				case 'php' : 
					$this->content='$'.$varname.'["'.$this->name.'"]='.$quote.str_replace('"','\\"',$this->value).$quote.';'."\n";
					break;
				default: $this->content=(!$ignoreEmpty || $this->value)?$this->name.'='.$this->value."\n":'';
			}
		}
		function _exectmpl_text () {
			$this->value=str_replace('"','&quot;',$this->value);
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_radio () {
			$options = array();
			$or=$this->_param->getAttribute( 'orientation' );
			if (!$or) $or="v";
			foreach ($this->_param->childNodes as $option) {
				$val = $option->getAttribute( 'value' );
				$text = $option->gettext();
				$options[] = new Option( $val, $text, $this->value,'checked="checked"',$this->name);
			}
			$this->_tmpl->addObject($this->_tmplname."_".$or, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option_".$or, $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname."_".$or);
			$this->_tmpl->freeTemplate( $this->_tmplname."_h", true );
			$this->_tmpl->freeTemplate( $this->_tmplname."_v", true );
			return $c;
		}
		function _exectmpl_checkbox () {
			$options = array();
			$or=$this->_param->getAttribute( 'orientation' );
			if (!$or) $or="v";
			$values=explode("|",$this->value);
			foreach ($this->_param->childNodes as $option) {
				$val = $option->getAttribute( 'value' );
				$text = $option->gettext();
				$options[] = new Option( $val, $text,$values ,'checked="checked"',$this->name);
			}
			$this->_tmpl->addObject($this->_tmplname."_".$or, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option_".$or, $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname."_".$or);
			$this->_tmpl->freeTemplate( $this->_tmplname."_h", true );
			$this->_tmpl->freeTemplate( $this->_tmplname."_v", true );
			return $c;
		}

		function _exectmpl_select () {
			$options = array();
			foreach ($this->_param->childNodes as $option) {
				$val = $option->getAttribute( 'value' );
				$text = $option->gettext();
				$options[] = new Option( $val, $text, $this->value,'selected');
			}
			
			if (!$this->size) $this->size=1;
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_newstype () {
			$mact=$this->_param->getAttribute( 'model' );
			$deftext=$this->_param->getAttribute( 'defaultoptiontitle' );
			if (!$mact) $mact='mlist';
			
			if($this->value===null) $this->value=-1;
			include("opt/news/include/inc.php");
			
			$options=array();
			foreach($NTYPEDEFS[$mact] as $k=>$v) {
				if (isset($NTYPEDEFS[$mact][$k]['title']))
					array_push($options,makeOption($k,str_replace('[INTROCOUNT]','N',$v['title']),$this->value));
			}
			if (!$this->size) $this->size=1;
			$this->_tmpl->addVar($this->_tmplname, 'defaultoptiontitle',$deftext);
			
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_tmplconf () {
			global $simConfig_template,$simConfig_absolute_path;
			$deftext=$this->_param->getAttribute( 'defaultoptiontitle' );
			$dir=$simConfig_absolute_path."/tmpl/$simConfig_template/config";
			$d = dir($dir);
			$options=array();
			while (false !== ($entry = $d->read())) if(!is_dir($dir.'/'.$entry) && substr($entry,0,1) != '.') {
				$pi=pathinfo($entry);
				if ($pi['extension']=='xml') 
					array_push($options,makeOption($pi['filename'],$pi['filename'],$this->value));
			}
			if (!$this->size) $this->size=1;
			$this->_tmpl->addVar($this->_tmplname, 'defaultoptiontitle',$deftext);
			
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_gallery () {
			$options = array();
			global $database;
			$q="SELECT c.id,l.title FROM gallery as c"
			."\n LEFT JOIN gallery_lang AS l ON c.id=l.id AND l.langID=1";
			$database->setQuery($q);
			$gl=$database->loadObjectList();
			foreach ($gl as $option) {
				$val = $option->id;
				$text = $option->title;
				$options[] = new Option( $val, $text, $this->value,'selected');
			}
			
			if (!$this->size) $this->size=1;
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$this->_tmpl->addObject($this->_tmplname."_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_tablelookup () {
			$options = array();
			global $database;
			$fid=$this->_param->getAttribute( 'valuefield' );
			$fnm=$this->_param->getAttribute( 'labelfield' );
			$tb=$this->_param->getAttribute( 'table' );
			$wh=$this->_param->getAttribute( 'filter' );
			$ord=$this->_param->getAttribute( 'ordering' );
			$wh=$wh?" WHERE ".$wh:'';
			$ord=$ord?" ORDER BY ".$ord:'';
			$q="SELECT $fid as id,$fnm as title FROM $tb $wh $ord";
			$database->setQuery($q);
			$gl=$database->loadObjectList();
			foreach ($gl as $option) {
				$val = $option->id;
				$text = $option->title;
				$options[] = new Option( $val, $text, $this->value,'selected');
			}
			
			if (!$this->size) $this->size=1;
			
			$this->_tmpl->addObject("conf_select", $this, "conf_",true);
			$this->_tmpl->addObject("conf_select_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate("conf_select");
			$this->_tmpl->freeTemplate("conf_select", true );
			return $c;
		}
		function _exectmpl_simplequery () {
			$options = array();
			global $database;
			$q="SELECT id,naziv FROM ".$this->_param->getAttribute( 'table' );
			$database->setQuery($q);
			$gl=$database->loadObjectList();
			$options[] = new Option( $this->_param->getAttribute( 'novalue' ), '-Default-', $this->value,'selected');
			foreach ($gl as $option) {
				$val = $option->id;
				$text = $option->naziv;
				$options[] = new Option( $val, $text, $this->value,'selected');
			}
			
			if (!$this->size) $this->size=1;
			$this->_tmpl->addObject("conf_select", $this, "conf_",true);
			$this->_tmpl->addObject("conf_select_option", $options, "opt_",true);
			$c= $this->_tmpl->getParsedTemplate("conf_select");
			$this->_tmpl->freeTemplate("conf_select", true );
			return $c;
		}
		function _exectmpl_list () {
			$this->value=str_replace("|","\n",$this->value);
			$this->rows=$this->_param->getAttribute( 'rows' );
			$this->cols=$this->_param->getAttribute( 'cols' );
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );			
			$this->_tmpl->clearTemplate($this->_tmplname); 
			return $c;
		}
		function _exectmpl_hierarchy () {
			global $database;
			$tb=$this->_param->getAttribute( 'table' );
			$ad=$this->_param->getAttribute( 'additional' );
			$valueprint=intval($this->value)?$database->loadResultArrayText("SELECT naziv FROM $tb WHERE id = ".$this->value."","<br/> "):'';
			
			$this->_tmpl->addVar($this->_tmplname, 'additional',$ad);
			$this->_tmpl->addVar($this->_tmplname, 'valueprint',$valueprint);
			$this->_tmpl->addVar($this->_tmplname, 'opt',$this->_param->getAttribute( 'opt' ));
			$this->_tmpl->addVar($this->_tmplname, 'act',$this->_param->getAttribute( 'act' ));
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
		function _exectmpl_multihierarchy () {
			global $database;
			$tb=$this->_param->getAttribute( 'table' );
			$ad=$this->_param->getAttribute( 'additional' );
			$valueprint=trim($this->value)?$database->loadResultArrayText("SELECT naziv FROM $tb WHERE id IN (".$this->value.")","<br/> "):'';
			$winlevel=!intval($this->_wlevel)?2:intval($this->_wlevel)+1;
			$this->_tmpl->addVar($this->_tmplname, 'winlevel',$winlevel);
			$this->_tmpl->addVar($this->_tmplname, 'additional',$ad);
			$this->_tmpl->addVar($this->_tmplname, 'valueprint',$valueprint);
			$this->_tmpl->addVar($this->_tmplname, 'opt',$this->_param->getAttribute( 'opt' ));
			$this->_tmpl->addVar($this->_tmplname, 'act',$this->_param->getAttribute( 'act' ));
			$this->_tmpl->addObject($this->_tmplname, $this, "conf_",true);
			$c= $this->_tmpl->getParsedTemplate($this->_tmplname);
			$this->_tmpl->freeTemplate( $this->_tmplname, true );
			return $c;
		}
	}
	
	class Option {
		var $name;
		var $value;
		var $text;
		var $id;
		var $sel;
		function Option ($v,$t,$sv,$st='checked="checked"',$n='',$i='') {
			$this->name=$n;
			$this->value=$v;
			$this->text=$t;
			$this->id=$i;
			if (is_array($sv)) {
				if (in_array($this->value,$sv)) $this->sel=$st;
			} else if ($this->value==$sv) $this->sel=$st;
			else $this->sel='';
		}
	}
	
	class Config {
		var $_list=array();
		var $_tmpl;
		var $_values;
		var $_xmlDoc;
		var $_configDefinitionFile;
		var $_configResultFile;
		var $id;		
		var $lang;		
		var $opt;		
		var $act;		
		var $group;		
		var $tmplCustom;		
		
		function Config($opt,$group,&$tmpl,$values,$idx=0,$langx='') {
			global $id,$lang,$simConfig_live_site,$simConfig_template;
			$this->id=($idx ? $idx : $id);
			$this->lang=($langx ? $langx : $lang);
			$this->opt=$opt;
			$this->tmplCustom=false;
			$this->group=$group;
			$this->_tmpl=$tmpl;
			$this->_values=$values;
			$this->_configResultFile='opt/'.$opt.'/config/'.$group.".php";
			
			
			require_once( 'inc/domit/xml_domit_lite_include.php' );
	
			$this->_xmlDoc = new DOMIT_Lite_Document();
			$this->_xmlDoc->resolveErrors( true );
			
			if (($opt=='admin') && (substr($group,0,4)=='mod_')) {
				$this->_configDefinitionFile=$simConfig_live_site.'/loadmoduleconfig.php?mod='.$group;
				//$this->_xmlDoc->setConnection($simConfig_live_site);
			} else if ($opt=='custom') {
				$this->tmplCustom=true;
				$this->_configDefinitionFile="tmpl/$simConfig_template/config/".$group.".xml";
				//$this->_xmlDoc->setConnection($simConfig_live_site);
			} else if(substr($group,0,5)=='tmpl-') {
					$this->tmplCustom=true;
					$group=substr($group,5);
					$this->group=$group;
					$this->_configDefinitionFile="tmpl/$simConfig_template/opt/".$opt."/config/".$group.".xml";
			} else $this->_configDefinitionFile='opt/'.$opt.'/config/'.$group.".xml";
			
			
			if ($this->_xmlDoc->loadXML( $this->_configDefinitionFile, false, true )) {
					
					$element =& $this->_xmlDoc->documentElement;
					
					if ($element->getTagName() == 'config' && $element->getAttribute( "type" ) == 'opt') {
						if ($element = &$this->_xmlDoc->getElementsByPath( 'params', 1 )) {
							 foreach ($element->childNodes as $param)  {
									
									$c=new ConfigElem($this->_tmpl,$param,$this->_values,$this->id);
									array_push($this->_list,$c);
							 }
						}
					}
			}
		
		}
		function printConfigForm(&$res,$title,$act,$wlevel='') {
			global $opt;
			$this->act=$act;
			if ($this->tmplCustom) $this->opt=$opt;
			
			for($i=0;$i<count($this->_list);$i++) $this->_list[$i]->createFormElement($wlevel);
			
			$this->_tmpl->readTemplatesFromInput( "conf/config.html");
			$this->_tmpl->addVar("config", 'wlevel', $wlevel);
			$this->_tmpl->addObject("config", $this, "C_",true);
			$this->_tmpl->addObject("config_content", $this->_list, "conf_",true);
			$cont= $this->_tmpl->getParsedTemplate("config");
			$this->_tmpl->freeTemplate( "config", true );
			
			$res->change('popuptitle'.$wlevel,$title);
		
			$res->change('ed_content'.$wlevel,$cont);
			$res->javascript("showEdit".$wlevel."Popup('popupdescription".$wlevel."');");
			
		}
		function saveConfigFile($varname) {
			$f=fopen($this->_configResultFile,"w");
			fputs($f,'<?'."\n");
			for($i=0;$i<count($this->_list);$i++) {
				$this->_list[$i]->createVarLine($varname);
				fputs($f,$this->_list[$i]->content);
			}
			fputs($f,"\n".'?>');
			fclose($f);
		}
		function getConfigText($ignoreEmpty=false) {
			$ret='';
			for($i=0;$i<count($this->_list);$i++) {
				$this->_list[$i]->createVarLine('','dbfield',$ignoreEmpty);
				$c=$this->_list[$i]->content;
				if(!$ignoreEmpty || $c) $ret.=$c;
			}
			return $ret;
		}
		function getConfigArray() {
			$ret=array();
			for($i=0;$i<count($this->_list);$i++) {
				$this->_list[$i]->createVarLine('','dbfield');
				$ret[$this->_list[$i]->name]=$this->_list[$i]->value;
			}
			return $ret;
		}				
	
	}


?>
