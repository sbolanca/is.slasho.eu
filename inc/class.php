<?
class simSettings extends simDBTable {
	var $userID=null;
	var $type=null;
	var $value=null;

	function simSettings( &$db ) {
		$this->simDBTable( 'settings', 'userID,type', $db );
		$this->_has_autoincrement=false;
	}
	
}
class simConfiguration extends simDBTable {
	var $type=null;
	var $value=null;

	function simConfiguration( &$db ) {
		$this->simDBTable( 'configuration', 'type', $db );
		$this->_has_autoincrement=false;
	}
	
	
}
class simPermission {
	var $username=null;
	var $opt=null;
	var $_list=array();

	function simPermission( $optx='',$username='' ) {
		global $opt;
		$this->opt=($optx ? $optx : $opt); 
		$this->username=($username ? $username : $_SESSION['MM_Username']); 
		$this->load();
	}
	function load($opt='',$username='') {
		global $database;
		if ($username) $this->username=$username;
		if ($opt) $this->opt=$opt;
		$database->setQuery("SELECT permission FROM permissions WHERE username='".$this->username."' AND opt='".$this->opt."'");
		$this->_list=$database->loadResultArray();
		
	} 
	function allow($perm='') {
		if ($this->username=='slasho') return true; else 
		if ($this->opt=='register') return true; else 
		if (!$perm) return count($this->_list);
		else return (in_array($perm,$this->_list));
	}
}

class simLog extends simDBTable {
	var $id=null;
	var $userID=null;
	var $title=null;
	var $subject=null;
	var $details=null;
	var $opt=null;
	var $act=null;
	var $dbindex=null;
	var $created=null;
	var $user=null;
	var $actionpath=null;
	var $ip=null;
	var $super=null;
	var $app=null;
	var $parentID=null;
	var $importance=null;
	var $_subs=array();

	function simLog( &$db,$opt='',$act='',$id=0,$myID=0,$isSuper=0,$app='is' ) {
		$this->simDBTable( 'log', 'id', $db );
		$this->opt=$opt;
		$this->act=$act;
		$this->dbindex=$id;
		$this->userID=$myID;
		$this->super=$isSuper;
		$this->actionpath=$_SERVER['QUERY_STRING'];
		$this->app=$app;
		if($app=='is') {
				$this->user=$_SESSION['MM_name'];
				$this->parentID=$_SESSION['MM_loginID'];
		}
		$this->ip=$_SERVER['REMOTE_ADDR'];
	}
	function createTblLog($tbl='',$dbindex='',$title='',$subject='',$subjectID=0) {
		$s_tbl=$tbl?$tbl:$this->opt;
		$s_dbindex=$dbindex?$dbindex:$this->dbindex;
		$s_title=$title?$title:$this->title;
		$s_subject=$subject?$subject:$this->subject;
		$sLog=new simLogTable( $this->_db,$s_tbl,$s_dbindex,$s_title,$s_subject,$subjectID);
		$sLog->check(true);
		$this->_subs[]=$sLog;
		return $sLog;
	}
	function savelog($ttl,$s='',$details='', $dbindex=-1,$tblLog=true) {
		$this->title=$ttl;
		$this->subject=$s;
		$this->details=$details;
		if ($dbindex>-1) $this->dbindex=$dbindex;
		if($tblLog) $this->createTblLog(); 
	}
	function saveIlog($il,$ttl,$s='',$details='', $dbindex=-1,$tblLog=true) {
		$this->importance=$il;
		$this->savelog($ttl,$s,$details, $dbindex,$tblLog);
	}
	function savelogNOW($ttl,$s='',$details='', $dbindex=-1,$opt='') {
		$dbi=$this->dbindex;
		$opti=$this->opt;
		$this->savelog($ttl,$s,$details, $dbindex);
		if ($opt) $this->opt=$opt;
		if (trim($this->title)) {
			$this->check(true);
			$this->store();
		}
		$this->id=null;
		$this->opt=$opti;
		$this->title=null;
		$this->subject=null;
		$this->details=null;
		$this->dbindex=$dbi;
	}
	function saveIlogNOW($il,$ttl,$s='',$details='', $dbindex=-1,$opt='') {
		$this->importance=$il;
		$this->savelogNOW($ttl,$s,$details, $dbindex,$opt);
	}
	
}
class simLogTable extends simDBTable {
	var $id=null;
	var $logID=null;
	var $title=null;
	var $subject=null;
	var $tbl=null;
	var $dbindex=null;
	var $subjectID=null;
	

	function simLogTable( &$db,$tbl='',$dbindex='',$title='',$subject='',$subjectID='' ) {
		$this->simDBTable( 'log_table', 'id', $db );
		$this->title=$title;
		$this->subject=$subject;
		$this->tbl=$tbl;
		$this->dbindex=$dbindex;
		$this->subjectID=$subjectID;
	}
	function store($lid=0) {
		if($lid) $this->logID=$lid;
		simDBTable::store();
	}	
}
class simGroup extends simDBTable {
	var $id=null;
	var $type=null;
	var $parentID=null;
	var $image=null;
	var $image2=null;
	var $position=null;
	var $ordering=null;
	var $published=null;
	var $title=null;
	var $menu=null;
	var $description=null;

	function simGroup( &$db ) {
		$this->simDBTable( 'groups', 'id', $db );
		$this->setAsHtml('description');
	}
	
}




class simGallery extends simDBTable {
	var $id=null;
	var $published=null;
	var $ordering=null;
	var $title=null;
	var $description=null;

	function simGallery( &$db ) {
		$this->simDBTable( 'gallery', 'id', $db );
	}
	
}


class simGalleryItem extends simDBTable {
	var $id=null;
	var $galleryID=null;
	var $image=null;
	var $published=null;
	var $ordering=null;
	var $title=null;
	var $description=null;

	function simGalleryItem( &$db ) {
		$this->simDBTable( 'gallery_item', 'id', $db );
	}
	function createCMandTagFields($var='CM_opt_content_photo') {
		$this->cm='oncontextmenu="cCM('.$this->id.','.$var.',event);"';
		$this->tagid='id="gi_'.$this->id.'"';
	}
	function addJSfield() {
		$this->js='';
		setThumbScript($this);
	}
}



class simFile extends simDBTable {
	var $id=null;
	var $file=null;
	var $view=null;
	var $title=null;
	var $description=null;
	var $itemID=null;

	function simFile( &$db ) {
		$this->simDBTable( 'file', 'id', $db );
	}
	
}


class simFolder extends simDBTable {
	var $id=null;
	var $naziv=null;
	var $parentID=null;
	var $userID=null;
	var $sharing=null;
	var $visibility=null;
	var $hide=null;
	var $cls1=null;
	var $cls2=null;
	var $cls3=null;
	var $cls4=null;
	var $cls5=null;
	var $komentar=null;
	var $created=null;
	var $changed=null;
	

	function getBojaName($ix) {
		$fld='cls'.$ix;
		$filterBoje=array('[plavo]','[zeleno]','[crveno]','[smeđe]','[ljubičasto]');
		if($ix>0) {
			if(trim($this->$fld)) return $this->$fld;
			else return $filterBoje[$ix-1];
		}
		else return 'bez markera';
	}
	
	function loadFull($id) {
		$this->load($id);
		$this->convertSQLDateToHR('created',true);
		$this->convertSQLDateToHR('changed',true);
		$this->addField('name',$this->_db->getResult("SELECT name FROM user WHERE id=".$this->userID));
		$v='';
		switch ($this->sharing) {
			case 0: $v='samo vlasnik'; break;
			case 4: $v='vlasnik, '.$this->_db->loadResultArrayText("SELECT name FROM user WHERE id IN (".$this->visibility.")",", "); break;
			case 5: $v='svi'; break;
		}
		$this->addField('vidljivost',$v);
	}
}

class simModule extends simDBTable {
	var $id=null;
	var $name=null;
	var $positionID=null;
	var $filter=null;
	var $param=null;
	var $subtemplate=null;
	var $ordering=null;
	var $published=null;


	function simModule( &$db ) {
		$this->simDBTable( 'module', 'id', $db );
	}

	
}



class simMessage extends simDBTable {
	var $id=null;
	var $type=null;
	var $mact=null;
	var $parentID=null;
	var $title=null;
	var $message=null;
	var $sender=null;
	var $phone=null;
	var $emfrom=null;
	var $emto=null;
	var $dbindex=null;
	var $fullmessage=null;
	var $viewed=null;
	var $created_date=null;
	var $_return=null;
	var $_return_failed=null;

	function simMessage( &$db ) {
		$this->simDBTable( 'message', 'id', $db );		
	}
	function sendMessage() {
		global $simConfig_mailfrom,$simConfig_fromname,$simConfig_sitename,$simConfig_live_site,$lang;
		$this->created_date=date("Y-m-d H:i:s");
		
		if (!trim($this->_return_failed)) $this->_return_failed="javascript:window.history.go(-1);";
		
		
		if (isset($_POST['code'])) {
			 session_start();
			 $code = $_POST['code'];
  			 if (!(md5($code) == $_SESSION['image_random_value'])) return 'codeerror'; 
		}
		
		//if (!trim($this->emfrom)) $this->emfrom=$simConfig_mailfrom;
		if (!trim($this->emto)) $this->emto=$simConfig_mailfrom;
		if (!trim($this->sender)) $this->sender=$simConfig_fromname;
		if (!trim($this->title)) $this->title="Mail sa ".$simConfig_live_site;
		if (!trim($this->fullmessage)) {
			$this->fullmessage="Upit od ".$this->sender." (email: ".$this->emfrom.", tel: ".$this->phone."):\r\n-------\r\n".$this->message;
			if (!trim($this->_return) && trim($this->type) && trim($this->mact)) 
				$this->_return=$simConfig_live_site."/index.php?opt=".$this->type."&act=".$this->mact."&id=".$this->dbindex."&lang=".$lang;
			$this->fullmessage.="\r\n-------------\r\nLink: ".$this->_return;
		}
		require_once( "inc/phpmailer/class.phpmailer.php" );
		
		if (simMail($simConfig_mailfrom,$this->sender,$this->emto,$this->title,$this->fullmessage)) {
			$this->check(false);
			$this->store();
			return 'ok';
		} else return 'failed';
		
	}
}

//---------------------------------------------------------------------------------



class simParams {
	var $_rows=array();

	function simParams($t,$def='') {
		$this->_rows=explode("\n",$t);
		$this->initRows();
		if (is_array($def)) $this->initDefault($def);
	}
	
	function initRows() {
		foreach($this->_rows as $row) if (trim($row)) {
			$rarr=explode("=",$row);
			$name=trim($rarr[0]);
			if(count($rarr)>0) $val=trim($rarr[1]); else $val=null;
			$this->$name=$val;
		}
	}
	function initDefault(&$def) {
		foreach($def as $k=>$v) if (!isset($this->$k) || ($this->$k=='')) $this->$k=$v;
	}
	function exportAssocArray() {
		$ret=array();
		foreach($this->_rows as $row) if (trim($row)) {
			$rarr=explode("=",$row);
			$name=trim($rarr[0]);
			if(count($rarr)>0) $val=trim($rarr[1]); else $val='';
			$ret[$name]=$val;
		}
		return $ret;
	}
	function getDef($p,$dArr,$def='') {
		if (isset($this->$p)) return $this->$p;
		else if (isset($dArr[$p])) return $dArr->$p;
		else return $def;
	}
	function get($p,$def='') {
		if (isset($this->$p) && !(($this->$p=='') && is_string($this->$p))) return $this->$p; else return $def;
	}
	function is($p,$def=false) {
		if (isset($this->$p)) return intval($this->$p); else return $def;
	}
	function is_set($p) {
		if (isset($this->$p) && !($this->$p==0) && !($this->$p=="0") && !($this->$p=='off') && !($this->$p=='no')) return true;
		else return false;
	}
}
class simOption {
	var $value=null;
	var $selected=null;
	var $text=null;
	
	function simOption($v,$t,$s) {
		$this->value=$v;
		$this->selected=(trim($s)==trim($v)) ? "selected" : "";
		$this->text=$t;
	}
}
class simTmplConditionPair {
	var $tmpl=null;
	var $name=null;
	var $value=null;
	
	function simTmplConditionPair($t,$n,$v) {
		$this->value=$v;
		$this->name=$n;
		$this->tmpl=$t;
	}
}


class simPagination {
	var $page;
	var $_pages=array();
	var $link;
	var $totalPages;
	var $pageLimit;
	var $SQLLimit;
	var $maxPagesOffset;
	var $pghp=0;
	var $pghn=0;
	function simPagination($totalRows,$pageLimit,$link,$maxPagesOffset,$pagename='page') {
		$this->totalRows=$totalRows;
		$this->pageLimit=$pageLimit;
		$this->maxPagesOffset=$maxPagesOffset;
		$this->link=$link;
		$this->page=intval(simGetParam($_REQUEST,$pagename,1)); 
		if (!$this->page) $this->page=1;
		$this->SQLLimit=($pageLimit*($this->page-1)).",".$pageLimit;
		$this->createLinks($link);
		$this->prev=$this->page-1; 
		$this->next=$this->page+1; 
		if ($this->prev==0) {$this->prev=1; $this->pghp=1; }						
		if ($this->page==$this->totalPages) {$this->next=$this->totalPages;	$this->pghn=1; }						
		
	}
	function createLinks($link) {
		//$pagesCnt=
		$this->totalPages=(1+floor(($this->totalRows-1)/$this->pageLimit));
		if ($this->totalPages>1) {
			if ($this->totalPages>($this->maxPagesOffset*2+1)) {
				$total=$this->maxPagesOffset*2+1;
				$start=max($this->page-$this->maxPagesOffset,1);
				$start=min($start,$this->totalPages-$this->maxPagesOffset*2);
			} else {
				$total=$this->totalPages;
				$start=1;
			}
			if ($this->page>1) {
				array_push($this->_pages,new simPLink('<<',$link."&amp;page=1", "first"));
				array_push($this->_pages,new simPLink('<',$link."&amp;page=".($this->page-1), "prev"));
			}
			for ($i=$start; $i<=($total+$start-1);$i++) {
				$p=new simPLink($i,$link."&amp;page=".$i,(($i==$this->page) ? "sel" : ""));
				array_push($this->_pages,$p);
			}
			if ($this->page<$this->totalPages) {
				array_push($this->_pages,new simPLink('>',$link."&amp;page=".($this->page+1), "next"));
				array_push($this->_pages,new simPLink('>>',$link."&amp;page=".($this->totalPages), "last"));
			}
		}
	}

}
class simPLink {
	var $title;
	var $link;
	var $class;
	function simPLink($t,$l,$c) {
		$this->title=$t;
		$this->link=$l;
		$this->class=$c;		
	}
}



class MysqlIndexing {
	var $arr=array();
	
	function get($skipFors='') {
		$fA=$skipFors?explode(",",$skipFors):array();
		$A=array();
		foreach ($this->arr as $type=>$fors)
			foreach($fors as $for=>$ixs) if(!in_array($for,$fA) && count($ixs)) $A[]=$type." INDEX ".$this->_for($for)."(".implode(",",$ixs).")";
		return implode(" ",$A);
	}
	function add($ix,$type='USE',$forChar='') {
		$for=$this->_createArrayIfNotExist($type,$forChar);
		if(!in_array($ix,$this->arr[$type][$for])) $this->arr[$type][$for][]=$ix;
	}
	function remove($ix,$types='USE',$fors='') {
		$fA=explode(",",$fors);
		$tA=explode(",",$types);
		foreach($tA as $type)
			foreach($fA as $for) {
				if(isset($this->arr[$type][$for]) && in_array($ix,$this->arr[$type][$for])) 
					$this->arr[$type][$for] = array_diff($this->arr[$type][$for], array($ix));
			}
	}
	function removeFromTypes($ix,$types='USE') {
		$tA=explode(",",$types);
		foreach($tA as $type) 
			foreach(array_keys($this->arr[$type]) as $for) if(in_array($ix,$this->arr[$type][$for])) 
				$this->arr[$type][$for] = array_diff($this->arr[$type][$for], array($ix));
	}
	function removeFromFor($ix,$fors='') {
		$fA=explode(",",$fors);
		foreach($fA as $for) {
			foreach(array_keys($this->arr) as $type) 
				if(isset($this->arr[$type][$for]) && in_array($ix,$this->arr[$type][$for])) 
				   $this->arr[$type][$for] = array_diff($this->arr[$type][$for], array($ix));
		}
	}
	function removeAll($ix) {
		$tA=explode(",",$types);
		foreach($tA as $type) 
			foreach(array_keys($this->arr[$type]) as $for)
				if(in_array($ix,$this->arr[$type][$for])) 
					$this->arr[$type][$for] = array_diff($this->arr[$type][$for], array($ix));
	}
	function clearAll() {
		$this->arr=array();
	}
	
	function _createArrayIfNotExist($type,$for) {
		if(!isset($this->arr[$type])) $this->arr[$type]=array();
		if(!isset($this->arr[$type][$for])) $this->arr[$type][$for]=array();
		return $for;
	}
	
	
	function _for($f) {
		switch(strtolower($f)) {
			case 'j': $for='FOR JOIN'; break;
			case 'g': $for='FOR GROUP BY'; break;
			case 'o': $for='FOR ORDER BY'; break;
			default: $for='';
		}
		return $for;
	}
}
class NodesArray 
{ 
   var $filter = 0; 
   var $curr=0;
    
   function FilterMethod($row) 
   { 
      //you need to replace $row['parent'] by your name of column, which is holding the parent's id of current entry! 
      return $row->parentID == $this->filter; 
   } 
    
   function CreateNestedArray(&$data, &$arr, $parent, $startDepth, $maxDepth) 
   { 
      if ($maxDepth-- == 0) return; 
      $index = 0; 
      $startDepth++; 

      $this->filter = $parent; 
      $children = array_filter($data, array($this, "FilterMethod")); 
      foreach ($children as $child) 
      { 
         $arr[$index] = $child; 
         $arr[$index]->depth = $startDepth; 
         //you need to replace $child['id'] by your name of column, which is holding the id of current entry! 
         $this->CreateNestedArray($data, $arr[$index]->children, $child->id, $startDepth, $maxDepth); 
         $index++; 
      } 
   } 
    
   function CreateResult($data,$parent, $startDepth, $maxDepth) { 
      $arr = array(); 
      $this->CreateNestedArray($data, $arr, $parent, $startDepth, $maxDepth); 
      return $arr; 
   } 
   
   function FlatNestedArray(&$data,$sp='',$ssp='',$md=1) {    
 	  $arr=array();   
      foreach ($data as $itm) { 
         $itmch=$itm->children;
		 $itm->children=null;
		 $itm->title=str_repeat($sp,$itm->depth-1).(($itm->depth>$md) ? $ssp : '').$itm->title;
		 array_push($arr,$itm); 		
		 if (is_array($itmch) && count(($itmch)>0))
        	 $arr=array_merge($arr,$this->FlatNestedArray($itmch,$sp,$ssp)) ;
      } 
	  return $arr;
   } 
   
   function sortDBNestedArray(&$items,$sp='',$ssp='',$md=1) {
  	$arr = $this->CreateResult($items, 0, 0, -1); 
	$arr2=$this->FlatNestedArray($arr,$sp,$ssp,$md);
	return $arr2;	
   }
} 


?>