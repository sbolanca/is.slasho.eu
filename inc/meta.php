<?
class simMainFrame {
	var $metatags=array();
	var $scripts=array();
	var $headerstyles=array();
	var $links=array();
	var $footerscripts=array();
	var $headerscripts=array();
	var $headeronloadscripts=array();
	var $conditionPairs=array();
	var $allowCM=true;
	var $deviceType;
	var $isAdmin;
	var $isSuper;	
	var $adminUsername;	
	var $adminName;	
	var $adminEmail;	
	var $adminID;
	var $title;
	var $templname;
	var $templpath;
	var $bodyActions;
	var $ba=array();
	var $showFooter;
	var $currPage;
	var $jah_debug0;
	var $openNavigation;
	var $jah_debug1;
	var $jah_hidemsg;
	var $vars=array();
	
	function simMainFrame($tname,$isAdmin,$title,$desc='',$keywords='') {
		$this->templateName=$tname;
		$this->templatePath="tmpl/".$tname;
		$this->deviceType=$_SESSION['deviceType'];
		$this->isAdmin=$isAdmin;
		if ($this->isAdmin) $this->currPage='index.php'; else $this->currPage='index.php';
		$this->setTitle($title);
		if ($keywords) $this->setMetaKeywords($keywords);
		if ($desc) $this->setMetaDescription($desc);
		$this->ba=createAssocNestedArray("onload");
		$this->vars=array();
		
	}
	function init() {
		$acts=array();
		foreach ($this->ba as $key => $value)
			if (count($value)) array_push($acts,$key.'="'.implode("; ",$value).'"');
		$this->bodyActions=implode(" ",$acts);
	}
	function addToVars($v,$n) {
		$this->vars[$n]=$v;
	}
	function enableUpload() {
		$this->includeScript("jq/upload/js/vendor/jquery.ui.widget.js","upload.widget");
		$this->includeScript("jq/upload/js/jquery.iframe-transport.js","ui.widget");
		$this->includeScript("jq/upload/js/jquery.fileupload.js","upload.fileupload");
		$this->includeScript("jq/upload/js/jquery.fileupload-process.js","upload.fileupload-process");
		
	}
	function addBodyAction($key,$action) {
		if (!array_key_exists($key,$this->ba)) $this->ba[$key]=array();
		array_push($this->ba[$key],$action);
	}
	function setTitle($title) {
		$this->title=$title;
	}
	function addMetaName($name,$content,$type='w') {
		$meta=new simMetaTag($name,$content);
		$this->putMetaTag($meta,$type);
	}
	function addMetaHTTPEquiv($httpe,$content,$type='w') {
		$meta=new simMetaTag($httpe,$content,true);
		$this->putMetaTag($meta,$type);
	}
	function putMetaTag(&$meta,$type='w') {
		switch($type) {
			 case 'a': $this->expandMetaTag($meta); break;
			 case 'n': $this->appendMetaTag($meta);  break;
			 default: $this->replaceMetaTag($meta);
		}
	}
	function addMetaKeywords($keywords) {
		$this->addMetaName("keywords",$keywords,'n');
	}
	function setMetaKeywords($keywords) {
		$this->addMetaName("keywords",$keywords);
	}
	function appendMetaKeywords($keywords) {
		$this->addMetaName("keywords",$keywords,'a');
	}
	function addMetaDescription($desc) {
		$this->addMetaName("description",$desc,'n');
	}
	function setMetaDescription($desc) {
		$this->addMetaName("description",$desc);
	}
	function appendMetaDescription($desc) {
		$this->addMetaName("description",$desc,'a');
	}
	function setMetaRobots($cont) {
		$this->addMetaName("robots",$cont);
	}
	function setMetaCopyright($cont) {
		$this->addMetaName("copyright",$cont);
	}
	function setMetaAuthor($cont) {
		$this->addMetaName("author",$cont);
	}
	function setMetaCacheControl($cont) {		
		$this->addMetaHTTPEquiv("cache-control",$cont);
	}
	function setMetaContentLanguage($cont) {		
		$this->addMetaHTTPEquiv("content-language",$cont);
	}
	function setMetaExpires($cont) {		
		$this->addMetaHTTPEquiv("expires",$cont);
	}
	function setMetaPragma($cont) {		
		$this->addMetaHTTPEquiv("pragma",$cont);
	}
	function setMetaRefresh($time,$url='') {		
		$this->addMetaHTTPEquiv("refresh",$time.($url? ';'.$url :  ''));
	}
	function removeMetaTag($name) {
		$this->removeElement($name);
	}
	function replaceMetaTag(&$meta) {		
		$ix=$this->getIndexOf($meta->name);
		if ($ix>-1) $this->metatags[$ix]=$meta;
		else array_push($this->metatags,$meta);
	}
	function expandMetaTag(&$meta) {
		$ix=$this->getIndexOf($meta->name);
		if ($ix>-1) $this->metatags[$ix]->appendContent($meta->content);
		else array_push($this->metatags,$meta);
	}
	function appendMetaTag(&$meta) {
		array_push($this->metatags,$meta);
	}
	function includeScript($src,$name='') {
		$ix=-1;
		$script=new simIncludeScript($src,$name);
		if ($name) $ix=$this->getIndexOf($name,'scripts');
		if ($ix>-1) $this->scripts[$ix]=$script;
		else array_push($this->scripts,$script);
	}
	function includeOnloadScript($src,$name='') {
		$ix=-1;
		$script=new simScript("'".$src."'",$name);
		if ($name) $ix=$this->getIndexOf($name,'headeronloadscripts');
		if ($ix>-1) $this->headeronloadscripts[$ix]=$script;
		else array_push($this->headeronloadscripts,$script);
	}
	function initOnloadScripts() {
		$tmp=array();
		foreach ($this->headeronloadscripts as $s) $tmp[]=$s->script;
		$this->addScript("var vnn_onloadscripts=new Array(\n".implode(",\n",$tmp)."\n);",'__initOnloadScripts');
	}
	function includeCMActions($o,$sub='',$showerror=true) {
	
		global $simConfig_alang;
		if ($this->isAdmin || $this->allowCM) {
			if (file_exists("opt/".$o."/js/cm_actions".$this->isSuper.".js"))
				//$this->includeOnloadScript("opt/".$o."/js/cm_actions".$sub.$this->isSuper.".js","opt".$o."cm");
				$this->includeScript(auto_version("opt/".$o."/js/cm_actions".$sub.$this->isSuper.".js"),"opt".$o."cm");
			else if (file_exists("opt/".$o."/js/cm_actions.js"))
				$this->includeScript(auto_version("opt/".$o."/js/cm_actions".$sub.".js"),"opt".$o."cm");
				//$this->includeOnloadScript("opt/".$o."/js/cm_actions".$sub.".js","opt".$o."cm");
				
			else if ($showerror) echo "VannaCMS warning: can't load $o cm_actions.<br/>";
		}
	}
	function removeIncludedScript($name) {
		$this->removeElement($name,'scripts');
	}
	function addCSS($src,$name) {
		$this->addLink($src,'stylesheet',"text/css",$name);
	}
	function removeCSS($name) {
		$this->removeLink($name);
	}
	function addLink($href,$rel,$type='',$name='') {
		$ix=-1;
		$link=new simIncludeLink($href,$rel,$type,$name);
		if ($name) $ix=$this->getIndexOf($name,'links');
		if ($ix>-1) $this->links[$ix]=$link;
		else array_push($this->links,$link);
	}
	function removeLink($name) {
		$this->removeElement($name,'scripts');
	}
	function addScript($script,$name='',$arrayname='headerscripts',$append=true) {
		$ix=-1;
		$scr=new simScript($script,$name);
		$arr=$this->$arrayname;
		if ($name) $ix=$this->getIndexOf($name,$arrayname);
		if ($ix>-1) {
			if ($append) $arr[$ix]->appendScript($script);
			else $arr[$ix]=$scr;
		} else array_push($this->$arrayname,$scr);
	}
	function addHeaderScript($script,$name='') {
		$this->addScript($script,$name);
	}
	function addFooterScript($script,$name='') {
		$this->addScript($script,$name,'footerscripts');
	}
	function addHeaderStyle($style,$name='') {
		$this->addScript($style,$name,'headerstyles');
	}
	function replaceHeaaderScript($script,$name) {
		$this->addScript($script,$name,'headerscripts',false);
	}
	function replaceHeaaderStyle($style,$name) {
		$this->addScript($style,$name,'headerstyles',false);
	}
	function replaceFooterScript($script,$name) {
		$this->addScript($script,$name,'footerscripts',false);
	}
	function removeScript($name,$arrayname='headerscripts') {
		$this->removeElement($name,$arrayname);
	}
	function removeHeaderScript($name) {
		$this->removeElement($name,'headerscripts');
	}
	function removeHeaderStyle($name) {
		$this->removeElement($name,'headerstyles');
	}
	function removeFooterScript($name) {
		$this->removeElement($name,'footerscripts');
	}

	function getIndexOf($name,$arrayname='metatags') {
		$i=0; $ix=-1;
		$arr=$this->$arrayname;
		while (($i<count($arr)) && ($ix<0)) {
			if ($arr[$i]->name==$name) $ix=$i;
			$i++;
		}
		return $ix;		
	}
	function removeElement($name,$arrayname='metatags') {
		$this->$arrayname=array_filter($this->$arrayname, create_function('&$v','return (!($v->name == "'.$name.'"));'));
	}
	function addConditionPair($tmpl,$name,$value) {
		$p=new simTmplConditionPair($tmpl,$name,$value);
		array_push($this->conditionPairs,$p);
	}
	//-------------------------------------
	function getModuleTemplatePath(&$module) {
		$modroot="mod/".$module->name;
		$root=$this->templatePath."/".$modroot;
		$modfile=trim($module->name).".html";
		if (trim($module->subtemplate)) $tmplfile=trim($module->subtemplate).".html";
		else $tmplfile=false;
		if ($tmplfile && is_file($root."/sub/".$tmplfile)) $ret=$modroot."/sub/".$tmplfile;
		else if (is_file($root."/".$modfile)) $ret=$modroot."/".$modfile;
		else $ret=false;
		return $ret;
	}
	
	
}


class simMetaTag {
	var $tag;
	var $name;
	var $isHttpEquiv;
	var $content;
	
	function simMetaTag($name,$content,$isHttpEquiv=false) {
		$this->name=$name;
		$this->content=$content;
		$this->isHttpEquiv=$isHttpEquiv;
		$this->generateTag();
	}
	function generateTag() {
		$this->tag='<meta '.($this->isHttpEquiv ? 'http-equiv' :'name' ).'="'.$this->name.'" content="'.$this->content.'">';
	}
	function appendContent($content,$sep=",") {
		$this->content.=($this->content ? $sep : "").$content;
		$this->generateTag();
	}
}

class simIncludeScript {
	var $tag;
	var $src;
	var $name;
	
	function simIncludeScript($src,$name='') {
		$this->src=$src;
		$this->name=$name;
		$this->generateTag();
	}
	function generateTag() {
		$this->tag='<script language="JavaScript" src="'.$this->src.'" type="text/JavaScript"></script>';
	}
}
class simIncludeLink {
	var $tag;
	var $href;
	var $rel;
	var $type;
	var $name;
	
	function simIncludeLink($href,$rel,$type,$name='') {
		$this->href=$href;
		$this->rel=$rel;
		$this->type=$type;
		$this->name=$name;
		$this->generateTag();
	}
	function generateTag() {
		$this->tag='<link href="'.$this->href.'" rel="'.$this->rel.'" '.($this->type ? 'type="'.$this->type.'"' : '').'>';
	}
}
class simScript {
	var $name;
	var $script;
	
	function simScript($script,$name='') {
		$this->name=$name;
		$this->script=$script;
	}
	function appendScript($script) {
		$this->script.=($this->script ? "\n" : "").$script;
	}
}



?>
