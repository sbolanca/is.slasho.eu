<?php

class jahAction {
  var $_block=array();
  var $_mainblock='';
  var $_target='';
  var $_position='';
  var $_command='alert';
   
	function jahAction($c='',$t='',$m='',$p='') {
		if ($c) $this->_command=$c;
		if ($t) $this->_target=$t;
		if ($m) $this->_mainblock=$m;
		if ($p) $this->_position=$p;
	}	
	function addBlock($cont,$t='cont') {
		array_push($this->_block,$t."|#|".$cont);
		return $cont;
	}

	function getActionString() {
	    if (($this->_command=="insert") || ($this->_command=="insertfull") || ($this->_command=="change") || ($this->_command=="delete") || ($this->_command=="replace") || ($this->_command=="changeselectbox")) {
			 if (!($this->_target)) $this->makeAlert("SISTEM JAH ERROR: ID targeta nije naveden");
		}
		if (($this->_command=="append") || ($this->_command=="insert") || ($this->_command=="insertfull")) {
			 if (!($this->_mainblock)) $this->makeAlert("SISTEM JAH ERROR: ID glavnog bloka nije naveden");
		}
		$ret=$this->_mainblock.'|#|'.$this->_target.'|#|'.$this->_command.'|#|'.$this->_position;
		
		if (count($this->_block)) $ret.='?#?'.implode('?#?',$this->_block);
		return $ret;
	}
	function makeAlert($a) {
		$this->_command="alert";
		$this->_block=array();
		$this->addBlock($a);
	}
}
class jahTab {
	var $id=null;
	var $action=null;
	var $title=null;
	var $norefresh=false;
	
	function jahTab($id,$t,$r,$a) {
		$this->id=$id;
		$this->action=$a;
		$this->title=$t;
		$this->norefresh=$r;
	}
}

class jahResponse {
  var $_action=array();
  var $_postaction=array();
  var $_tabcontent=null;
  var $_dialogFooter=null;
  var $_dialogTitle=null;
  var $_tabs=array();
  
  function jahResponse() {
  		
  }
  
  function addTab($ix,$t,$r=false,$a='') {
	global $opt,$pageopt,$act,$pageact,$pagetmpl,$id;
	if (!$a) $a="tab.php?pageopt=$pageopt&pageact=$pageact&pagetmpl=$pagetmpl&opt=$opt&act=$act&tab=$ix&id=$id";
	$tab=new jahTab($ix,$t,$r,$a);
	$this->_tabs[]=$tab;
  }
  function addTabsAssoc(&$list) {
	foreach($list as $l=>$n) $this->addTab($l,$n);

  }
  function addTabs($list) {
	foreach(explode(",",$list) as $l) $this->addTab($l,$l);

  }
  
  function setTabContent($c,$t='') {
		$this->_tabcontent=$c;
		if($t) $this->_dialogTitle=$t;
  }
  function setDialogFooter($c,$dialogName='1') {
		$this->_dialogFooter=$c;
		$this->change('dfooter'.$dialogName,$this->_dialogFooter);
		
  }
  function setDialogTitle($t) {
		$this->_dialogTitle=$t;
  }
   function printTab() {
		echo $this->_tabcontent;
  }
  function closeDialog($dialogName='1') {
		$this->javascript('$("#dialog'.$dialogName.'").dialog( "close" );');
  }
  function closeSimpleDialog($wnum='1') {
		$this->javascript('if(dw'.$wnum.') $("#dialogwindow'.$wnum.'").dialog( "close" );');
  }
 
  function openSimpleDialog($ttl,$cont,$w,$wnum='1',$class='') {
	$this->javascript('if(dw'.$wnum.' && !$("#dialogwindow'.$wnum.'" ).dialog("isOpen")) {$( "#dialogwindow'.$wnum.'").dialog( "destroy" );dw'.$wnum.'=null;}');
	$this->change('dialogwindow'.$wnum,$cont);
	$this->javascript('$( "#dialogwindow'.$wnum.'").show();');
	$this->javascript('$("#dialogwindow'.$wnum.'").attr("title","'.addslashes($ttl).'")');
	$this->javascript('if(!dw'.$wnum.' || !$("#dialogwindow'.$wnum.'" ).dialog("isOpen")) dw'.$wnum.'=$("#dialogwindow'.$wnum.'" ).dialog({resizable:false,width:'.$w.',minWidth:'.$w.($class? ',dialogClass: "dialog-'.$class.'"':'').'}).dialog("widget").draggable("option","containment","none"); else  {$( "#dialogwindow'.$wnum.'").dialog("option","title","'.addslashes($ttl).'");$( "#dialogwindow'.$wnum.'").dialog("open")}');
	$this->javascript("$('form').attr('autocomplete', 'off');");
	
  }
  function showTabs($openTab,$w,$h,$dialogName='1',$class='') {
		$this->javascript('if(dd'.$dialogName.' && !$("#dialog'.$dialogName.'" ).dialog("isOpen"))  {$( "#dialog'.$dialogName.'").dialog( "destroy" );dd'.$dialogName.'=null}');
		$this->javascript('$( "#dialog'.$dialogName.'").show();');
		$this->javascript('if(tt'.$dialogName.') {$( "#tabovi'.$dialogName.'").tabs( "destroy" );tt'.$dialogName.'=null;}');
		$this->javascript('$("#tabovi'.$dialogName.'").height('.$h.');');
		$this->change('dcontent'.$dialogName,$this->_tabcontent);
		$tabs=array(); $activeIndex=0;$i=0;
		foreach($this->_tabs as $t) {
			
			if($t->id==$openTab) $activeIndex=$i;
			$tabs[]='<li id="tab_'.$t->id.'"'.($t->norefresh?' refresh="no"':'').'><a href="'.($t->id==$openTab?'#dcontent'.$dialogName:$t->action).'" id="tab_title_'.$t->id.'">'.$t->title.'</a></li>';
			$i++;
		}
		$this->change('tabs'.$dialogName,implode('',$tabs));
		$this->javascript('tt'.$dialogName.'=$("#tabovi'.$dialogName.'").tabs({heightStyle: "fill",active:'.$activeIndex.',load: function( event, ui ) {onTabLoad(ui)},create: function( event, ui ) {onTabLoad(ui)},activate: function( event, ui ) {onTabActivate(ui)}} );');
		$this->javascript('$("#dialog'.$dialogName.'").attr("title","'.addslashes($this->_dialogTitle).'")');
		$this->javascript('if(!dd'.$dialogName.' || !$("#dialog'.$dialogName.'" ).dialog("isOpen")) { DDcenter = DDcenter + 10; DDtop = DDtop + 10; dd'.$dialogName.'=$("#dialog'.$dialogName.'" ).dialog({title: "'.addslashes($this->_dialogTitle).'" ,resizable:false,width:'.$w.',	minWidth:'.$w.($class? ',dialogClass: "dialog-'.$class.'"':'').',position: { my: ("center+"+ DDcenter + " top+" + (DDtop-'.($h/2).')), at: "center", of:window },close: function(event, ui) { DDcenter = DDcenter - 10;DDtop = DDtop - 10;}}).dialog("widget").draggable("option","containment","none");} else  {$( "#dialog'.$dialogName.'").dialog("option","title","'.addslashes($this->_dialogTitle).'");$( "#dialog'.$dialogName.'").dialog("open")}');
		$this->javascript("$('form').attr('autocomplete', 'off');");
		//$this->javascript('dd=$("#dialog'.$dialogName.'" ).dialog({resizable:false});');
		
		//switch the titlebar class
  }
  function setTabTitle($tid,$t) {
	$this->javascript('$("#tab_title_'.$tid.'").html("'.$t.'")');
  }
  function addAction(&$a,$post=false) {
  		if ($a && (strtolower(get_class($a))=='jahaction')) {
			if(!$post) array_push($this->_action,$a);
			else array_push($this->_postaction,$a);
		}
  }
  function javascript($script,$post=false) {
  		$a=new jahAction('javascript');
		$a->addBlock($script);
		$this->addAction($a,$post);
		return $script;
  }
  function change($target,$cont,$post=false) {
  		$a=new jahAction('change',$target);
		$a->addBlock($cont);
		$this->addAction($a,$post);
  }
  function replace($target,$cont,$post=false) {
  		$a=new jahAction('replace',$target);
		$a->addBlock($cont);
		$this->addAction($a,$post);
  }
  function insert($target,$cont,$block,$pos='',$reo='') {
  		$a=new jahAction('insert',$target,$block,$pos);
		$a->addBlock($cont);
		if ($reo) $a->addBlock("Sortable.create('$block',".'{'.$reo.'});','jscr');
		$this->addAction($a);
  }
  function append($cont,$block,$pos='',$reo='') {
  		$a=new jahAction('append','',$block,$pos);
		$a->addBlock($cont);
		if ($reo) $a->addBlock("Sortable.create('$block',".'{'.$reo.'});','jscr');
  }
  function after($target,$cont,$post=false) {
  		$a=new jahAction('after',$target);
		$a->addBlock($cont);
		$this->addAction($a,$post);
  }
  function before($cont,$block,$post=false) {
  		$a=new jahAction('before',$target);
		$a->addBlock($cont);
		$this->addAction($a,$post);
  }

  function insertfull($target,$cont,$block,$pos='',$reo='') {
  		$a=new jahAction('insertfull',$target,$block,$pos);
		$a->addBlock($cont);
		if ($reo) $a->addBlock("Sortable.create('$block',".'{'.$reo.'});','jscr');
		$this->addAction($a);
  }
  function alert($msg) {
		
  		$a=new jahAction('alert');
		$a->addBlock($msg);
		$this->addAction($a);

  }
  function simAlert($msg) {
		
  		$a=new jahAction('simalert');
		$a->addBlock($msg);
		$this->addAction($a);

  }
 function confirm($msg,$script='',$cancelscript='') {
		
  		$a=new jahAction('confirm');
		$a->addBlock($msg);
		$a->addBlock($script);
		$a->addBlock($cancelscript);
		$this->addAction($a);

  }
  function customAlert($msg,$modal=false,$func='') {
	
		$m=$modal?"{modal: true}":'';
		$m=$func?"{modal: true,close: function(event,ui){ $func }}":$m;
		$this->javascript('$( "#dialogmessage" ).html("'.nl2br($msg).'").dialog('.$m.');');
  }
  function delete($id,$msg='') {
   		global $hideMSG;
  		$a=new jahAction('delete',$id);
		if ($msg && !$hideMSG) $a->addBlock($msg);
		$this->addAction($a);
  }
  
   function go_to($loc,$post=false) {
  		$a=new jahAction('javascript');
		$a->addBlock("location.href='index.php?$loc';");
		$this->addAction($a,$post);
  }
  function selectbox($box,$options) {
  	$a=new jahAction('changeselectbox',$box);
	$a->addBlock($options);
	$this->addAction($a);
  }
  function suggest(&$rows) {
  	global $sugest_elementid;
  	$a=new jahAction('suggest',$sugest_elementid);
	$cont='';
	foreach($rows as $row) $cont.='<suggest id="'.$row->id.'" '.((isset($row->js) && $row->js) ? 'js="'.$row->js.'"' : '').((isset($row->value) && $row->value) ? ' value="'.$row->value.'"' : '').'>'.str_replace("&","&amp;",$row->text).'</suggest>';
	$a->addBlock($cont);
	$this->addAction($a);
  }
  function setClass($elid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "$('#$elid').prop('className','$cls');" );
		$this->addAction($a);
		return $return;
  }
  function addClass($elid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "$('#$elid').addClass('$cls')" );
		$this->addAction($a);
		return $return;
  }

  function removeClass($elid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "$('#$elid').removeClass('$cls')" );
		$this->addAction($a);
		return $return;
  }
  function changeCMVar($elid,$ix,$var) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "changeCMVar('$elid',$ix,$var)" );
		$this->addAction($a);
		return $return;
  }
  function setCMVars($elid) {
		$allargs=func_get_args();
		if (count($allargs)>1) $aA=array_slice($allargs,1);	
		else $aA=array();
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "setCMVars('$elid','".implode(",",$aA)."')" );
		$this->addAction($a);
		return $return;
  }
  
  function printResponse() {
  	$tmp=array();
  	 foreach($this->_action as $a) array_push($tmp,$a->getActionString());
	 foreach($this->_postaction as $a) array_push($tmp,$a->getActionString());
	 echo implode("+#+",$tmp); 
  }
  function changeRowValues($gridvar,&$row,$fields,$rid='') {
  		if (!$rid) $rid=$row->id;
		$fArr=explode(',',$fields);
		$exe='';
		for($i=0;$i<count($fArr); $i++)	{
			$f=$fArr[$i];
			if (isset($row->$f))
			 $exe.=$gridvar.".cells('$rid',$i).setValue('".str_replace("'","\\'",$row->$f)."');";
			 else $exe.=$gridvar.".cells('$rid',$i).setValue('');";
		}
		$a=new jahAction('javascript');
		$return=$a->addBlock("if ($gridvar.getRowIndex('$rid')>-1) { $exe }");
		$this->addAction($a);
		return $return;
  }
  function changeRowValuesByObject($gridvar,&$row,$fields,$rid='') {
  		if (!$rid) $rid=$row->id;
		$fArr=explode(',',$fields);
		$exe='';
		for($i=0;$i<count($fArr); $i++)	{
			$f=$fArr[$i];
			if (isset($row->$f))
			$exe.=$gridvar.".cells('$rid',$i).setValue('".str_replace("'","\\'",$row->$f)."');";
			//else $exe.=$gridvar.".cells('$rid',$i).setValue('');";
		}
		$a=new jahAction('javascript');
		$return=$a->addBlock("if ($gridvar.getRowIndex('$rid')>-1) { $exe }");
		$this->addAction($a);
		return $return;
  }
  function changeCellValue($gridvar,$rid,$idx,$val) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("if ($gridvar.getRowIndex('$rid')>-1) $gridvar.cells('$rid',$idx).setValue('".str_replace("'","\\'",$val)."');");
		$this->addAction($a);
		return $return;
  }
  function changeCellValueByCode($gridvar,$rid,$code,$val) {
   		$fldsArr=explode(",",$_SESSION[$gridvar."_fields"]);
		if (in_array($code,$fldsArr)) return $this->changeCellValue($gridvar,$rid,array_search($code,$fldsArr),$val);
		else return '';
  }

  function addRow($gridvar,&$row,$fields,$pos='-',$rid='') {
  		if (!$rid) $rid=$row->id;
		$posp='';
		if (!($pos=='-')) $posp=','.$pos;
		$fArr=explode(',',$fields);
		foreach($fArr as $i=>$f) $fArr[$i]=$row->$f;
		$exe=$gridvar.".addRow('$rid','".addslashes(implode('|',$fArr))."'$posp);";		
  		$a=new jahAction('javascript');
		$return=$a->addBlock($exe);
		$this->addAction($a);
		return $return;
  }
   function deleteRow($gridvar,$rid) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("if ($gridvar.getRowIndex('$rid')>-1) $gridvar.deleteRow('$rid');");
		$this->addAction($a);
		return $return;
  }
  function selectRow($gridvar,$rid) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock($gridvar.".setSelectedRow('$rid',false,false,false);");
		$this->addAction($a);
		return $return;
  }
  function markRow($gridvar,$rid,$bgcolor='#ffffcc',$color='',$rest='') {
  		$a=new jahAction('javascript');
		$return=$a->addBlock(($bgcolor ? $gridvar.".setRowColor('$rid','$bgcolor');" : '')
		.(($color || $rest ) ? $gridvar.".setRowTextStyle('$rid','".($color ? "color:$color;" : '' ).($rest ? "$rest" :'')."');" : ""));
		$this->addAction($a);
		return $return;
  }
  function setRowStyle($gridvar,$rid,$style) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( $gridvar.".setRowTextStyle('$rid','$style');" );
		$this->addAction($a);
		return $return;
  }
  function setRowClass($gridvar,$rid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "if ($gridvar.getRowIndex('$rid')>-1) $gridvar.getRowById('$rid').className='$cls';" );
		$this->addAction($a);
		return $return;
  }
  function addRowClass($gridvar,$rid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "if ($gridvar.getRowIndex('$rid')>-1) $($gridvar.getRowById('$rid')).addClass('$cls')" );
		$this->addAction($a);
		return $return;
  }

  function removeRowClass($gridvar,$rid,$cls) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock( "if ($gridvar.getRowIndex('$rid')>-1) $($gridvar.getRowById('$rid')).removeClass('$cls')" );
		$this->addAction($a);
		return $return;
  }


  function rowCM($gridvar,$rid,$data) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("if ($gridvar.getRowIndex('$rid')>-1) $gridvar.setUserData('$rid','cm','$data');");
		$this->addAction($a);
		return $return;
  }
  function nodeCM($gridvar,$rid,$data) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("$gridvar.setUserData('$rid','cm','$data');");
		$this->addAction($a);
		return $return;
  }
    function addNode($gridvar,&$row,$text='',$parent='',$rid='') {
  		if (!$rid) $rid=$row->id;
  		if (!$parent) $parent=$row->parentID;
  		if (!$text && isset($row->title)) $text=$row->title;
		$exe=$gridvar.".insertNewChild('$parent','$rid','$text');";
		
  		$a=new jahAction('javascript');
		$return=$a->addBlock($exe);
		$this->addAction($a);
		return $return;
  }
   function changeNode($gridvar,&$row,$text='',$rid='') {
  		if (!$rid) $rid=$row->id;
  		if (!$text && isset($row->title)) $text=$row->title;
		$exe=$gridvar.".setItemText('$rid','$text');";
		
  		$a=new jahAction('javascript');
		$return=$a->addBlock($exe);
		$this->addAction($a);
		return $return;
  }
   function deleteNode($gridvar,$rid='') {
		$exe=$gridvar.".deleteItem('$rid');";
		
  		$a=new jahAction('javascript');
		$return=$a->addBlock($exe);
		$this->addAction($a);
		return $return;
  }
  function setNodeColor($gridvar,$rid,$aCol,$sCol='') {
  		$a=new jahAction('javascript');
		$return=$a->addBlock($gridvar.".setItemColor('$rid','$aCol'".($sCol ? ",'$sCol'" : "").");" );
		$this->addAction($a);
		return $return;
  }
  function execjah($opt,$act,$id=0,$param='') {
  		if ($param) $param="&".$param; 
  		$a=new jahAction('javascript');
		$return=$a->addBlock("window.g_Send('opt=$opt&act=$act&id=$id".$param."','GET');" );
		$this->addAction($a);
		return $return;
  }
  function execjahP($opt,$act,$id=0,$param='') {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("currentItem=$id;activateCMCommandGETP('$opt','$act','$param');" );
		$this->addAction($a);
		return $return;
  }
  function alertOK($post=false) {
		$a=new jahAction('alertOK');
		$this->addAction($a,$post);
  }
  function sound($s,$post=false) {
  		$a=new jahAction('javascript');
		$return=$a->addBlock("playFx('$s');" );
		$this->addAction($a,$post=false);
		return $return;
  }

}



?>
