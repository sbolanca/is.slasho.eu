if (parseInt(navigator.appVersion)>3) 
 if (navigator.appName=="Netscape") _brw="N";
 else  if (navigator.appName.indexOf("Microsoft")!=-1) _brw="M";

var popw=null;

function GetContents(field,edid)
{
	var oEditor = FCKeditorAPI.GetInstance(edid) ;
	field.value=oEditor.GetXHTML( true )  ;
}
function initEditor(edid,h,w,hide)
{
	if (document.getElementById(edid)) {
		if(!h) h=450;
		if(!w) w='100%';
		oFCKeditor = new FCKeditor( edid) ;
		var sBasePath = '/edit/FCKeditor/' ;
		//alert(sBasePath);
		oFCKeditor.BasePath	= sBasePath ;
		//oFCKeditor.ToolbarSet	= 'Default' ;
		oFCKeditor.ToolbarSet	= 'Basic' ;
		oFCKeditor.Width = w;
		oFCKeditor.Height = h;
			if (hide) oFCKeditor.Config['ToolbarStartExpanded'] = false ;
	
		oFCKeditor.ReplaceTextarea();	
	}
}
function activateCMCommand(opt,act,param) {
	if (param) param='&'+param;
	else param='';
	window.g_Send('pageopt='+pageopt+'&pageact='+pageact+'&pagetmpl='+pagetmpl+'&opt='+opt+'&act='+act+'&id='+currentItem+'&cidx='+currentColIndex+param,'GET');
}
function aCMC(ix,opt,act,param) {
	currentItem=ix;
	activateCMCommand(opt,act,param);
}
function activateCMCommandPOST(opt,act,frm,param,extra) {	
	if (!extra) extra='';
	if (param) param='&'+param; else param='';
	window.g_Send('pageopt='+pageopt+'&pageact='+pageact+'&pagetmpl='+pagetmpl+'&opt='+opt+'&act='+act+'&id='+currentItem+'&cidx='+currentColIndex+param,'POST',frm,extra);
}
function aCMCP(ix,opt,act,param) {
	currentItem=ix;
	activateCMCommandGETP(opt,act,param);
}
function activateCMCommandGETP(opt,act,param) {	
	if (!param) param='';
	var pArr=param.split('&'); var pArrcommand=new Array(); var parName=new Array();
	for(var i=0;i<pArr.length;i++) {
		var pA=pArr[i].split("=");
		pArrcommand.push("setGeneratedField('_GETPform','"+pA[0]+"','"+pA[1].replace(/\\/g,"'").replace(/\'/g,"\\'")+"')");
		parName.push(pA[0]);
	}
	var newfrm=generateHiddenForm('_GETPform',parName.join(','));
	eval(pArrcommand.join(';')) ;

	window.g_Send('pageopt='+pageopt+'&pageact='+pageact+'&pagetmpl='+pagetmpl+'&opt='+opt+'&act='+act+'&id='+currentItem+'&cidx='+currentColIndex,'POST','_GETPform');
}
function aCMCp(ix,opt,act,param) {
	currentItem=ix;
	activateCMCommandGETP(opt,act,param);
}
function saveConfig(opt,act) {	
	var frm='configForm';
	window.g_Send('pageopt='+pageopt+'&pageact='+pageact+'&pagetmpl='+pagetmpl+'&opt='+opt+'&act='+act+'&id='+currentItem,'POST',frm);
}
function activateCMCommandORD(opt,act,vars,param) {
	if (param) param='&'+param;
	else param='';
	window.g_Send('pageopt='+pageopt+'&pageact='+pageact+'&pagetmpl='+pagetmpl+'&opt='+opt+'&act='+act+'&id='+currentItem+param,'POST','ordering',vars);
}
function aCMCord(ix,opt,act,vars,param) {
	currentItem=ix;
	activateCMCommandORD(opt,act,vars,param);
}
function generateHiddenForm(name,fields,action) {
	if (document.forms[name]) {
			var newform=document.forms[name];
			var lg=newform.elements.length;
			for (var i=0; i<lg;i++) {
					var remEl = document.getElementById(newform.elements[lg-1-i].id);
					if ( remEl.parentNode && remEl.parentNode.removeChild ) {
						remEl.parentNode.removeChild(remEl);
					}
			}
				
	} else {
		var newform=document.createElement('form');
		newform.id=name;
		newform.name=name;
	}
	newform.action=action;
	var farr=new Array();
	farr=fields.split(',');
	for (var i=0;i<farr.length;i++) {
		var newel=document.createElement('input');
		newel.type='hidden';
		newel.id=name+'_'+farr[i];
		newel.name=farr[i];
		newform.appendChild(newel);
	}
	document.body.appendChild(newform);
	return newform;
}
function setGeneratedField(frmname,fldname,value) {
		var el=document.getElementById(frmname+'_'+fldname);
		el.value=value;
}
//---------------------------------------------------

function setObjToMiddle(obj) {
	
 if (isNN) {
	var ol = self.pageXOffset+(window.innerWidth/2-Number(obj.offsetWidth)/2);  
	var ot = self.pageYOffset + (window.innerHeight/2-Number(obj.offsetHeight)/2);	
  } else {
	var ol = 0 - obj.offsetWidth/2 + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth )/2 + ( ignoreMe = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft )   ;	
	var ot= 0 - obj.offsetHeight/2 + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight )/2 + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop )   ;
  }
  	if (ol<0) ol=0;
	if (ot<0) ot=0;
	obj.style.left=ol+"px"
	obj.style.top=ot+"px"
}
 var g_PopupIFrame;
function showStandardPopup(id) {
	var obj = document.getElementById(id); 
 	setObjToMiddle(obj);
	setZIndex(obj.style);
	

	obj.style.visibility = 'visible'; 
}
function setVisibilityHTMLElement(id,vis) {
	var obj = document.getElementById(id); 
	obj.style.visibility = vis; 
}
function hideStandardPopup(id,empty) {
	var obj = document.getElementById(id); 
	obj.style.visibility = 'hidden';
	if (empty) document.getElementById(empty).innerHTML=''; 
	eval ("if (!(typeof "+id+"_ef==='undefined') && "+id+"_ef) var oncl="+id+"_ef; else var oncl='';"+id+"_ef='';");
	if (oncl) document.getElementById(oncl).focus();
	//obj.style.display="none";
	
}
function showEditPopup(wel,ef) {
	showEPopup('editbox','ed_content','editdrag','etabs',wel,ef);
}
function showEdit2Popup(wel,ef) {
	showEPopup('editbox2','ed_content2','editdrag2','etabs2',wel,ef);
	//alert(wel);
}
function showEdit3Popup(wel,ef) {
	showEPopup('editbox3','ed_content3','editdrag3','etabs3',wel,ef);
	//alert(wel);
}

function showEPopup(bx,ct,dr,tb,wel,ef) {
	var tabsbox = document.getElementById(tb); 
	var stylebox = document.getElementById(bx); 
	var stylecont = document.getElementById(ct); 
	var styletrack = document.getElementById(dr); 
	var titlefield = styletrack.firstChild;
	tabsbox.style.display="none";
	if (wel) 	var welobj= document.getElementById(wel);
	else welobj=false;
	if (ef) eval (bx+"_ef='"+ef+"';");

		stylebox.style.height=(stylecont.offsetHeight+0+styletrack.offsetHeight)+"px";
		if (welobj) stylebox.style.width=welobj.offsetWidth+"px";
	titlefield.style.width=(styletrack.offsetWidth-30)+"px";

	showStandardPopup(bx);
	
}
function refreshEPopup(x) {
	if (!x) var x='';
	var stylebox = document.getElementById('editbox'+x); 
	var stylecont = document.getElementById('ed_content'+x); 
	var styletrack = document.getElementById('editdrag'+x); 
	stylebox.style.height=(stylecont.offsetHeight+styletrack.offsetHeight)+"px";
}
function showTabs(tb) {
	if (!tb) tb='';
	var tabsbox = document.getElementById('etabs'+tb); 
	tabsbox.style.display="block";
	var stylebox = document.getElementById('editbox'+tb); 
	var stylecont = document.getElementById('ed_content'+tb); 
	var styletrack = document.getElementById('editdrag'+tb); 
	stylebox.style.height=(stylecont.offsetHeight+tabsbox.offsetHeight+styletrack.offsetHeight)+"px";
}
function showDebugger(txt) {
	var obj = document.getElementById('debugger'); 
	var objc = document.getElementById('debugger_content'); 
	setObjToMiddle(obj);
	objc.value=txt;
 	setZIndex(obj.style);
	obj.style.left="0px";
	obj.style.visibility = 'visible'; 
	//hideSelect();
}
//----------------------------------------------------------------------

function changeIm(id,func,model,fld) {
	_insertImage(id,func,model,fld);
}


var imbox='';
var imvalue='';

function _insertImage(id,func,model,fld) {
	//alert(navigator.userAgent);
	if (!model) model='';
	if (!fld) fld='';
	if (document.getElementById(id)) {
		if(document.getElementById(id).src) var gotopath=document.getElementById(id).src;
		else if($("#"+id).css('background-image')) gotopath=$("#"+id).css('background-image').replace('url(','').replace(')','');
	} else var gotopath='';	
	gotopath=gotopath.replace(simConfig_live_site+"/files/Image/",'');
	imbox=func;
	imvalue=id;
	popx=window.open("popup/insert_image.php?model="+model+"&subfolder="+fld+"&gp="+gotopath,"popup", "resizable: yes; help: no; status: no; scroll: no; ");
	//popw.globalDoc=window;
	popx.imbox=func;
	popx.imvalue=id;

}

function makeThumb(fn) {
	fn_last=fn.lastIndexOf("/");
	if (fn_last>-1) {
		ret=fn.substring(0,fn_last)+"/thumbs"+fn.substr(fn_last);
	} else ret='thumbs/'+fn;
	return ret;
}
function doit(retVal,func,id,ch,model) {
		var fname=extractImageFile(retVal);
		eval(func);
}

	function extractImageFile(u) {
		var root=simConfig_live_site+"/files/Image/";
		var f;
		f=u.replace(root,"");
		return f;
	}

// -------------------------------------------------

function changeFile(id,func,fld) {
	//alert(navigator.userAgent);
	if (!fld) fld='';
	popw=window.open("popup/MediaBrowser.php?subfolder=&mediaType="+fld,"popup", "resizable: yes; help: no; status: no; scroll: no; ");
	popw.globalDoc=window;
	popw.imbox=func;
	popw.imvalue=id;
}

function doitf(retVal,func,id) {
		var fname=extractFileFile(retVal);
		eval(func);
}

	function extractFileFile(u) {
		var root=simConfig_live_site+"/files/File/";
		var f;
		f=u.replace(root,"");
		return f;
	}

// -------------------------------------------------
// -------------------------------------------------
function mouseX(evt) {
	if (!evt) evt = window.event; 
	if (evt.pageX) return evt.pageX; 
	else if (evt.clientX) return evt.clientX + (document.documentElement.scrollLeft ?  document.documentElement.scrollLeft : document.body.scrollLeft); 
	else return 0;
} 
function mouseY(evt) {
	if (!evt) evt = window.event; 
	if (evt.pageY) return evt.pageY; 
	else if (evt.clientY) return evt.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
	else return 0;
}
function follow(evt,divName,offX,offY) {
	if (document.getElementById) {
		var obj = document.getElementById(divName).style; 
		obj.visibility = 'visible'; 
		obj.left = (parseInt(mouseX(evt))+offX) + 'px';  
		obj.top = (parseInt(mouseY(evt))+offY) + 'px';
		setZIndex(obj);
	}
} 
function showHelp(tekst,evt) {
	var hw=document.getElementById('helpWindow');
	hw.innerHTML=tekst;
	if (tekst)	follow(evt,'helpWindow',0,0);	
}
function hideHelp() {
	var hw=document.getElementById('helpWindow');
	hw.style.visibility = 'hidden'; 
}
function showAtCursor(eid,evt) {
	var el = document.getElementById(eid); 
	var obj = el.style; 
	obj.left = (parseInt(mouseX(evt))) + 'px';
	var ot=parseInt(mouseY(evt));
	if (isNN) var ch=window.innerHeight; else  var ch=document.body.clientHeight;
	if (ot>(ch-el.offsetHeight)) ot=ot-el.offsetHeight+10;
	if (ot<0) ot=0;
	obj.top = (ot) + 'px';
	obj.visibility = 'visible'; 
	setZIndex(obj);
}
lastZIndex=100000;
function setZIndex(obj) {obj.zIndex=lastZIndex*1+1; lastZIndex= obj.zIndex; };
function showUnderElement(el,target) {
	var obj = document.getElementById(el).style; 
	var tobj = document.getElementById(target); 
	obj.left = (parseInt(getX(tobj))) + 'px';  
	obj.top = (parseInt(getY(tobj)) +tobj.offsetHeight + 1) + 'px';
	obj.visibility = 'visible'; 
	setZIndex(obj);
}
function showNearElement(eid,target,ofs) {
	if (!ofs) var ofs=0;
	var el = document.getElementById(eid); 
	var obj=el.style;
	var tobj = document.getElementById(target); 
	obj.left = (parseInt(getX(tobj)) + tobj.offsetWidth +1 +ofs) + 'px'; 
	var ot=parseInt(getY(tobj));
	if (isNN) var ch=window.innerHeight; else  var ch=document.body.clientHeight;
	if (ot>(ch-el.offsetHeight)) ot=ot-el.offsetHeight+10;
	if (ot<0) ot=0;
	obj.top = (ot) + 'px';
	obj.visibility = 'visible'; 
	setZIndex(obj);
}

function getY( oElement ){
	var iReturnValue = 0;
	while( oElement != null ) {
		iReturnValue += oElement.offsetTop;
		oElement = oElement.offsetParent;
	}
	return iReturnValue;
}
function getX( oElement ){
	var iReturnValue = 0;
	while( oElement != null ) {
		iReturnValue += oElement.offsetLeft;
		oElement = oElement.offsetParent;
	}
	return iReturnValue;
}
// --------------------------------------------------
function filex() {
	popw=window.open("popup/filex/index.php","popup", "width:400px; height:400px; resizable: yes; help: no; status: no; scroll: no; ");
	
}



// --------------------------------------------------

function submitFCKForm(frm,fld,fid,opti,acti) {
			eval('GetContents(document.'+frm+'.'+fld+',"'+fid+'");');
			g_Send('opt='+opti+'&act='+acti,'POST',frm);
			return false;
}

function copyLang(msg,frm,opti,acti,lngc) {
	var _a=document.getElementById('_fld_all');
	var _f=document.getElementById('_fld_force');
	if (!(_a.checked && _f.checked)) activateCMCommandPOST(opti,acti,frm);
	else {
		if (!lngc) lngc=opti;
		if (msg) eval('alert(LNGC_'+lngc+'.'+msg+')');
	}
}

//--------------------------------------------------
function RefreshSelectBox(boxes,o,a,condb,clearb,param) {
	if (!param) param='';
	else param='&'+param;
	if (!condb) condb='';
	if (!clearb) clearb='';
	var boxesArr=boxes.split("|");
	var varsArr=new Array();
	var test=true;
	for (var i=0;i<boxesArr.length; i++) {
			var p=document.getElementById(boxesArr[i]);
			varsArr.push('var'+i+'='+p.options[p.selectedIndex].value);
	}
	if (condb) {
		var condbArr=condb.split("|");
		for (var i=0;i<condbArr.length; i++) {
			var c=document.getElementById(condbArr[i]);
			switch (c.type) {
					case 'select': case 'select-one': case 'select-multiple':
						if (c.options[c.selectedIndex].value<1) test=false; break;
					case 'radio': case 'checkbox':
						if (!c.checked) test=false; break;
					default: if ((c.value=='0') || !(c.value)) test=false; 
			}
		}
	}
	if (test) activateCMCommand(o,a,varsArr.join("&")+param);
	else if (clearb) {
		var clearbArr=clearb.split("|");
		for (var i=0;i<clearbArr.length; i++) clearSelectOptions(clearbArr[i]);
	}
}
function clearSelectOptions(bx) {
			var cb=document.getElementById(bx);
			while (cb.options.length>0) cb.remove(0);
			cb.value=0;
}
function changeElementClass(el,cl) {
		var docel=document.getElementById(el);
		docel.className=cl;
}
function getEventTarget(evt) {
	var targ;
	if (!evt) var evt = window.event;
	if (evt.target) targ = evt.target;
	else if (evt.srcElement) targ = evt.srcElement;
	if (targ.nodeType == 3) targ = targ.parentNode;
	return targ;	
}
//-------------------------------------------------------------------
function selectSuggestion(evt) {
	var search_suggest=document.getElementById('search_suggest');
		

	if (search_suggest.style.visibility == 'visible') {
		switch(evt.keyCode) {
			case 40: highlightCurrentSuggestion(1); break;
			case 38: highlightCurrentSuggestion(-1); break;
			case 13: if (currentSuggestion>-1) 
				document.getElementById('search_suggest').childNodes[currentSuggestion].click();
				clearSuggestion();
			 break;
		}
		
	}
	el=getEventTarget(evt);
	suggestionCurrentString=el.value;
}
stppp=0;
function highlightCurrentSuggestion(step) {
	var os=currentSuggestion;
	var ss_children=document.getElementById('search_suggest').childNodes;
	if (ss_children.length>0) {
			if (currentSuggestion>-1) ss_children[currentSuggestion].className='suggest_link';
			currentSuggestion=currentSuggestion+step;
			if (currentSuggestion>(ss_children.length-1)) currentSuggestion=ss_children.length-1;
			else if (currentSuggestion<0) currentSuggestion=-1;
			if (currentSuggestion>-1) ss_children[currentSuggestion].className='suggest_link_over';
			ss_children[currentSuggestion].scrollIntoView(false);
	}
	stppp++;
	//document.getElementById('ptitle').innerHTML=stppp; 
}

function searchSuggest(elid,opt,act,param) {
	var ret=false;
	if (param) param='&'+param; else param='';
	var str = document.getElementById(elid).value;
	if (!suggestionTimeout && !(suggestionCurrentString==str)) {
		if (str.length>1) { 
		var newfrm = document.getElementById('_tempsuggestfrm');
		if (!newfrm) newfrm=generateHiddenForm('_tempsuggestfrm','suggest_string');
		setGeneratedField('_tempsuggestfrm','suggest_string',str);
		 window.g_Send('opt='+opt+'&act='+act+'&id='+currentItem+'&elementid='+elid+param,'POST','_tempsuggestfrm');	
		 currentSuggestion=-1;
		 ret=true;
		}  else {
			hideStandardPopup('search_suggest'); 
			currentSuggestion=-1; 
		}			
	}
	suggestionTimeout=true;
	setTimeout("suggestionTimeout=false;",100);
	return ret;
}

function setSuggestion(elid,suggid,val) {
	if (document.getElementById(elid) && document.getElementById(suggid)) {
		if (val) document.getElementById(elid).value=val.replace("&amp;","&");			
		else document.getElementById(elid).value=document.getElementById(suggid).innerHTML.replace("&amp;","&");			
	}
	clearSuggestion();
}
function clearSuggestion() {
	var search_suggest=document.getElementById('search_suggest');
	while(search_suggest.childNodes.length>0) {
 		search_suggest.removeChild(search_suggest.childNodes[search_suggest.childNodes.length-1]);
	}
	search_suggest.innerHTML='';
	hideStandardPopup('search_suggest');
	currentSuggestion=-1;
}
currentSuggestion=-1;
suggestionCurrentString='';
suggestionTimeout=false;
function attachSuggestFunctions(n,o,el) {
		var fnct=function () {
				if (o.attributes.getNamedItem('js')) eval(o.attributes.getNamedItem('js').value);
				if (o.attributes.getNamedItem('value')) setSuggestion(el,n.id,o.attributes.getNamedItem('value').value);	
				else setSuggestion(el,n.id);	
		};
		var fnover=function() {document.getElementById(n.id).className='suggest_link_over';}
		var fnout=function() {document.getElementById(n.id).className='suggest_link';}
		attachFunction(n,'click',fnct);
		//n.click = function() { n.handleEvent('onclick'); }

		attachFunction(n,'mouseover',fnover);		
		attachFunction(n,'mouseout',fnout);		
}




function suggestCHK(o,tp,fld,param) {
	var ret=false;
	ret=searchSuggest('inp_'+tp+'_'+fld,o,'suggest-'+fld,param); 
	if (ret) {
		document.getElementById('chk_'+tp+'_'+fld).checked=true;
		document.getElementById('hdn_'+tp+'_'+fld).value='0';
	}
}
function suggestCHKSelected(id,tp,fld) {
		if (document.getElementById('chk_'+tp+'_'+fld)) document.getElementById('chk_'+tp+'_'+fld).checked=false;
		document.getElementById('hdn_'+tp+'_'+fld).value=id;
}


//-------------------------------
function attachFunction(el,ev,func) {
	if (document.all) el.attachEvent('on'+ev, func );
	else  {
		el.addEventListener(ev, func, false);
	}
}
if (typeof document.addEventListener == "function")
  var registerEventHandler = function(node, event, handler) {
    node.addEventListener(event, handler, false);
  };
else
  var registerEventHandler = function(node, event, handler) {
    node.attachEvent("on" + event, handler);
  };
function detachFunction(node, event, handler) {
 if (typeof node.removeEventListener == "function")
    node.removeEventListener(event, handler, false);
  else
    node.detachEvent("on" + event, handler);
}

function normaliseEvent(event) {
  if (!event.stopPropagation) {
    event.stopPropagation = function() {this.cancelBubble = true;};
    event.preventDefault = function() {this.returnValue = false;};
  }
  if (!event.stop) {
    event.stop = function() {
      this.stopPropagation();
      this.preventDefault();
    };
  }

  if (event.srcElement && !event.target)
    event.target = event.srcElement;
  if ((event.toElement || event.fromElement) && !event.relatedTarget)
    event.relatedTarget = event.toElement || event.fromElement;
  if (event.clientX != undefined && event.pageX == undefined) {
    event.pageX = event.clientX + document.body.scrollLeft;
    event.pageY = event.clientY + document.body.scrollTop;
  }
  if (event.type == "keypress") {
    if (event.charCode === 0 || event.charCode == undefined)
      event.character = String.fromCharCode(event.keyCode);
    else
      event.character = String.fromCharCode(event.charCode);
  }

  return event;
}
/*
function attachFunction(node, type, handler) {
  function wrapHandler(event) {
    handler(normaliseEvent(event || window.event));
  }
  registerEventHandler(node, type, wrapHandler);
  return {node: node, type: type, handler: wrapHandler};
}
*/
function removeHandler(object) {
  detachFunction(object.node, object.type, object.handler);
}


function shd(id,tid) {	
		if (!tid) var tid='tblhead';
		var rows=$('#'+id+' div');
		var txt='<table><tr><td bgcolor="#aaaaaa" colspan="2" align="right"><div class="popupclosebtn" onClick="hideStandardPopup(\'detailsWindow\');"></div></td></tr>';
		$('#'+tid+' div').each(function(i){
			if($(this).html()) txt+='<tr><td class="dh" nowrap="nowrap">'+$(this).html()+'</td><td  nowrap="nowrap" class="dd">'+$(rows[i]).html()+"</td></tr>";
			
		});
		txt+='</table>';

		$('#detailsWindow').html(txt);
		showStandardPopup('detailsWindow');
}
function shdR(tid,id) {		
		var head=document.getElementById(tid);
		var row=document.getElementById(id);
		var details=document.getElementById('dWContent');
		var txt='<table>';
		for(var i=1;i<head.rows[0].cells.length;i++) {
			txt+='<tr><td class="dh" nowrap="nowrap">'+getInnerTag(head.rows[0].cells[i])+'</td><td  nowrap="nowrap" class="dd">'+getInnerTag(row.cells[i])+"</td></tr>";
		}
		txt+='</table>';
		details.innerHTML=txt;
		showStandardPopup('detailsWindow');
}
function getInnerTag(node) {
		if (node.nodeType==3) return node.parentNode.innerHTML;
		else if (!(node.value===undefined)) return node.value;
		else if (node.childNodes.length) return getInnerTag(node.childNodes[0]);
		else return '';
}

function showAdminForm(mtitle,ctitle,optsarr,typ) {	
	
		var aft=document.getElementById('adminformtext');
		var aftt=document.getElementById('adminformtitle');
		aftt.innerHTML=mtitle;
		aft.innerHTML=ctitle;
		var apanel=document.getElementById('adminformcontent');
		while(apanel.childNodes.length>0) apanel.removeChild(apanel.childNodes[0]);
		var ihtml='<table  cellpadding="1">';
		if (typ=='chk') { 
			var chktype='checkbox'; 
			var chkname='cnfinput[]'; 
		} else {
			var chktype='radio'; 
			var chkname='cnfinput'; 			
		}
		for (var i=0;i<optsarr.length;i++) {
				var pair=optsarr[i].split('|');
				var ch='';
				if (pair.length>2) {
					if (pair[2]=='1') ch='checked';
					else { eval("var chopt=("+pair[2]+");"); if (chopt) ch='checked'; }
				}
				ihtml+='<tr><td align="right" valign="top"><input id="cnfinput'+i+'" type="'+chktype+'" name="'+chkname+'" value="'+pair[0]+'" '+ch+'/></td>';
				var pmmsg=pair[1];
				ihtml+='<td align="left" width=100%"><label for="cnfinput'+i+'">'+pmmsg+'</label></td></tr>';
		}
		ihtml+='<tr><td align="right" colspan="2"><input type="button" value=">>>" onClick="adminFormOK('+optsarr.length+',\''+typ+'\')"/></td></tr>';
		ihtml+='</table>';
		apanel.innerHTML=ihtml;
		showStandardPopup('formwindow');		
		//showEPopup('formwindow','adminformx','formdrag','adminformx');
}
function adminFormOK(cnt,typ) {
		cnfResultVar=''; 
		var arr=new Array();
		for(var j=0;j<cnt;j++) {
				var el=document.getElementById('cnfinput'+j);
				if (el.checked) {
					if (typ=='chk') arr.push(el.value);
					else cnfResultVar=el.value;
				}
		}
		if (typ=='chk') cnfResultVar=arr.join('|');
		if ((typ=='sel') && !cnfResultVar) {
				alert('Niste ništa odabrali.');
				return;
		}
		hideStandardPopup('formwindow');
		cnffunction();
}
function replaceElementId(o,n) {
	var el=document.getElementById(o);
	if (el) el.id=n;
}





//-----------------table-----------------------

function doOnTblCM(id,cid,evt,tbl,cmname){
		if (tbl.selectedRows.length<2) tbl.setSelectedRow(id,false,false,false);
		stdRowSelected(id,cid);		
		var ud=	tbl.getUserData(id,'cm');
		if (ud || (ud=='0')) ud=','+ud;
		eval('cCM(id,\''+cmname+'\',evt'+ud+')');
}
function getCMVarsTblArray(id,tbl) {
	var ud=	tbl.getUserData(id,'cm');
	if (ud || (ud=='0'))  return ud.split(",");
	else return new Array();
}
function getCMVarByIndex(id,tbl,pos) {
	var vA=getCMVarsTblArray(id,tbl);
	return vA[pos];
}
function changeCMVarByIndex(id,tbl,pos,val) {
	var ud=	tbl.getUserData(id,'cm');
	if (ud || (ud=='0'))  var a=ud.split(",");
	else var a= new Array();
	a[pos]=val;
	tbl.setUserData(id,'cm',a.join(','));
}
function doOnTreeCM(id,evt,tr,cmname){
		tr.selectItem(id);
		stdRowSelected(id,0);		
		var ud=	tr.getUserData(id,'cm');
		if (ud || (ud=='0')) ud=','+ud;
		else ud='';
		eval('cCM(id,\''+cmname+'\',evt'+ud+')');
}
function activateTblCommand(opt,act,rid,param) {
	if (param) param='&'+param;
	else param='';
	window.g_Send('opt='+opt+'&act='+act+'&id='+rid+'&cidx='+currentColIndex+param,'GET');
}
//table cell edited - > send post data
function TCEP(opt,act,rid,cdx,fldname,fldval,param,cidxn) {	
	if (!cidxn) var cidxn='cidx';
	if (param) param='&'+param; else param='';
	var newfrm=generateHiddenForm('_tmpform','id,'+cidxn+','+fldname);
	setGeneratedField('_tmpform','id',rid);
	setGeneratedField('_tmpform',cidxn,cdx);
	setGeneratedField('_tmpform',fldname,fldval);
	activateCMCommandPOST(opt,act,'_tmpform',param);
}
function setTableCols(tbl,comm) {
	var arr=new Array();
	eval ("var flds="+tbl+"_fields.split(',')");
	for (var i=0; i<flds.length; i++) eval ("arr.push("+tbl+"_"+comm+"."+flds[i]+");");
	return arr.join("|");
}
function setHeaderTable(tbl,tname) {
	if(!tname) var tname=tbl;
	eval ("var flds="+tbl+"_fields.split(',')");
	var aligns=new Array();
	var titles=new Array();
	for (var i=0; i<flds.length; i++) eval ("titles.push("+tbl+"_H."+flds[i]+");aligns.push('text-align:'+"+tbl+"_A."+flds[i]+");");
	eval (tname+".setHeader(titles.join('|'),null,aligns)");
}
function getTableWidths(tbl) {
	var arr=new Array();
	for(var i=0;i<tbl.getColumnCount();i++) arr.push(tbl.getColWidth(i));
	return arr.join(",");
}
function stdRowSelected(i,x){currentItem=i; currentColIndex=x; hideContext()}
function getSelectedIds(tbl,curid) {
		var inselrows=false;
		for (var i=0;i<tbl.selectedRows.length;i++) if (curid==tbl.selectedRows[i].idd) inselrows=true;
		return (inselrows ? tbl.getSelectedId() : curid);	
	}
var subvisible=0;
function showSM(id) {
	if (!document.getElementById('ctxm'+id)) id=0;	
	var sold=document.getElementById('ctxm'+subvisible);	
	var snew=document.getElementById('ctxm'+id);	
	sold.style.display='none';
	snew.style.display='block';
	 subvisible=id;
}
function listAlbum(a) {location.href='index.php?opt=snimka&act=album&albumID='+a;}
function listSAlbum(s) {location.href='index.php?opt=snimka&act=album&snimkaID='+s;}
function getObjectClass(obj) {   
    if (obj && obj.constructor && obj.constructor.toString) {   
        var arr = obj.constructor.toString().match(   
            /function\s*(\w+)/);   
  
        if (arr && arr.length == 2) {   
            return arr[1];   
        }   
    }   
  
    return undefined;   
} 



//------------------------------------------------------

function getCO(x) {
	eval ('var c=cO.m'+x+';');
	return c;
}

function opw(id) {
	var rw=document.getElementById('xs_'+id);
	var sb=document.getElementById('rcn_'+id);
		sb.style.display='block';
		rw.childNodes[0].innerHTML='-';
}
function clw(id) {
	var rw=document.getElementById('xs_'+id);
	var sb=document.getElementById('rcn_'+id);
		sb.style.display='none';
		rw.childNodes[0].innerHTML='+';
}
function sww(id) {
	var rw=document.getElementById('xs_'+id);
	if (rw.childNodes[0].innerHTML=='+') opw(id);
	else clw(id);
}

function gsOArr(xid) {
	eval('var pom=sO.m'+xid+';');
	return pom;
}
function sSS(id,xid) {
	var rw=document.getElementById('xc_'+id);
	if (rw.childNodes[0].innerHTML=='°') {
		rw.childNodes[0].innerHTML="";
		rw.className="";
		pom=gsOArr(xid);
		for (var i=0;i<pom.length;i++) if (pom[i]==id) eval('sO.m'+xid+'.splice(i,1);');
	}
	else {
		rw.childNodes[0].innerHTML='°';
		eval('sO.m'+xid+'.push(id);');
		rw.className="rwsel";
	}
}
//------------------------------
 refreshIntervalID=setInterval(refreshSession,300000);
canRefresh=true;
function refreshSession() {
	if (canRefresh) activateCMCommand('','');
}
function openHierarchy(o,itemsscript,prefiks,ttl,hd,nullType,add,script) {
	if (!script) var script='';
	if (!add) var add='';
	if (!nullType) var nullType='';
	newfrm = document.getElementById('_temphyform');
	if (!newfrm) newfrm=generateHiddenForm('_temphyform','selectedID,prefiks,itemspath,title,heading,nullType,script');
	setGeneratedField('_temphyform','selectedID',document.getElementById(prefiks+'_ID').value);
	setGeneratedField('_temphyform','prefiks',prefiks);
	setGeneratedField('_temphyform','itemspath',o+'-jah-hierarchy-'+itemsscript);
	setGeneratedField('_temphyform','title',ttl);
	setGeneratedField('_temphyform','heading',hd);
	setGeneratedField('_temphyform','nullType',nullType);
	setGeneratedField('_temphyform','script',script);
	activateCMCommandPOST('admin','hierarchy-list','_temphyform',add);
}
hySelected=0;
function hySel(ix) {
		
	if (document.getElementById('hyt_'+hySelected)) document.getElementById('hyt_'+hySelected).className='';
	hySelected=ix;
	document.getElementById('hyselectedid').value=ix;
	document.getElementById('hyt_'+hySelected).className='hys';
}
function hySelMLT(ix,v,s,f) {
	if (!v) var v="Hy";
	if (!s) var s=0; if (!f) var f=0;
	var el=document.getElementById('hyt_'+ix);
	var inA=false; var i=0;
	eval ("selAM=sel"+v+"Array");
	while ((i<selAM.length) && !inA) { if (selAM[i]==ix) inA=true; i++; }
	switch (f) {
		case 1: el.className=''; if (inA) selAM.splice(i-1,1); break;
		case 2: el.className='hys';selAM.push(ix);	break;
		default:
			if (inA) {el.className='';selAM.splice(i-1,1);f=1; }
			else {el.className='hys';selAM.push(ix);f=2;}
	}
	if (s) {
		var li=document.getElementById('hy_'+ix);foundUL=-1;var k=0;
		while ((k<li.childNodes.length) && (foundUL<0)) { if (li.childNodes[k].nodeName.toUpperCase()=='UL') foundUL=k ; k++; }
		if (foundUL>-1) {
			var sli=li.childNodes[foundUL].childNodes;
			for (var j=0;j<sli.length;j++) if (sli[j].nodeName.toUpperCase()=='LI') hySelMLT(sli[j].id.substr(3),v,1,f); 
		}
	}
}
function openMultiHierarchy(o,a,id) {
	window.g_Send('opt='+o+'&act='+a+'&id='+id,'GET');
}
function clearHyFields(f) {
	document.getElementById(f+'_ID').value=0;
	document.getElementById(f+'_title').innerHTML='';
}
function setHyFields(f) {
	document.getElementById(f+'_ID').value=0;
	document.getElementById(f+'_title').innerHTML='';
	if (hySelected) {
		document.getElementById(f+'_ID').value=hySelected;
		document.getElementById(f+'_title').innerHTML=document.getElementById('hyt_'+hySelected).innerHTML;
		
	}
}
function setHyFieldsMulti(f,v,sep) {
	if (!sep) sep=', ';
	document.getElementById(f+'_ID').value=selCatArray.join(',');
	var tA=new Array();
	eval ("selAM=sel"+v+"Array");
	for(var i=0;i<selAM.length;i++) tA.push(document.getElementById('hyt_'+selCatArray[i]).innerHTML);
	document.getElementById(f+'_title').innerHTML=tA.join(sep);
}
function clearHyFieldsMulti(v) {
	eval ("selAM=sel"+v+"Array");
	for(var i=0;i<selAM.length;i++) {
		var el=document.getElementById('hyt_'+selAM[i]).className='';
	}
	eval ("sel"+v+"Array=new Array()");
}
function switchDisplay(id) {
	var el=document.getElementById(id);
	if (el.style.display=='block') el.style.display='none'; else el.style.display='block';
}
function new_tab(url )
{
  var win=window.open(url, '_blank');
  win.focus();
}
function gotoTableRow(tblName) {
	var aft=document.getElementById('adminformtext');
	var aftt=document.getElementById('adminformtitle');
	aftt.innerHTML='Pronalaženje retka u tablici';
	aft.innerHTML='Upiši redni broj reda koji želiš da bude pronađen u tablici i selektiran:';
	var apanel=document.getElementById('adminformcontent')
	apanel.innerHTML='<input name="srcrw" id="srcrw" size="10" value=""/></td>';
	apanel.innerHTML+='<input type="button" value="Nađi red" onClick="searchTableRow('+tblName+',document.getElementById(\'srcrw\').value)"/>';
	showStandardPopup('formwindow');
	document.getElementById('srcrw').focus();
}
function searchTableRow(tbl,ix) {
	if (ix<=tbl.getRowsNum()) {
		tbl.scrollToIndex(ix*1-1);
		hideStandardPopup('formwindow');
	} else alert('Tablica nema toliko redaka!');
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return (i+1);
    }
    return false;
}
function escClose(wnm,evt,nxt) {
	if (!evt) var evt = window.event;
	if(evt.keyCode==27) {
		hideStandardPopup(wnm);
		if (nxt) document.getElementById(nxt).focus();
	}
}
function v2t(ix) {
	if (document.getElementById(ix)) document.getElementById(ix).title=document.getElementById(ix).value;
}

	
function loadTable(tbl,optactlink,cnt,esr,step) {
		if(!step) var step=100;
		tbl.attachEvent("onDataReady",function(){$('#cnt').html(tbl.getRowsNum());});
		tbl.clearAll();
		tbl.enablePreRendering(30);
		tbl.enableSmartRendering(esr,step);
		tbl.loadXML("ajx.php?"+optactlink+'&total_count='+cnt);
	
	}
function simConfirm(t,q,yt,yf) {
	yff=function(){
		 $("#dialogconfirm").dialog('close');
		 yf();
		
	}

	switch(deviceType) {
		case 'tablet': w="50%"; break;
		case 'mobile': w="75%";  break;
		default: w=400; 
	}

	$("#dialogconfirm").dialog({
        resizable: false,
        modal: true,
        title: t,
		width:w,
        buttons: [
           {text: yt, click: function(){
				 $("#dialogconfirm").dialog('close');
				 yf();
		
			}}
			,
           {text:"Odustani",click: function () {
                $(this).dialog('close');
            }}
        ]
    }).html(q);
}
function simAlert(q) {
	$( "#dialogmessage" ).html(q).dialog({modal:true,width:500});
}
function initFileUpload(uplform,sbmtitle,pbar) {
	if(!pbar) pbar='progresswindow';
	$("#"+pbar+' .progressbar').progressbar({
			value: 0
		});
	$('#'+uplform+' .uplfield').fileupload({
        dataType: 'json',
		//acceptFileTypes: /(\.|\/)(zip,rar)$/i,
        maxFileSize: 500000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        data.context = $('#'+uplform+' .uploadpreview');
		data.context.html('');
        $.each(data.files, function (index, file) {
			var node = $('<p/>')
                    .append($('<span/>').text(file.name));
            
            node.appendTo(data.context);
			$('#'+uplform+' .uplnamefield').val(file.name);
				
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context);
        if (file.preview) {
			
			 //node.prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }

    }).on('fileuploadprogressall', function (e, data) {
		$('#'+pbar).show();
	
        var progress = parseInt(data.loaded / data.total * 100, 10);
		$("#"+pbar+" .progressbar").progressbar('value', progress);
			//$('#progress').val(progress);
		//$('#percentdata').html(progress + '%');
    }).on('fileuploaddone', function (e, data) {
		$('#'+pbar).hide();
        $.each(data.result.files, function (index, file) {
            if (file.url) {
				$('#'+uplform+' .uplurlfield').val(file.url);
				$('#'+uplform+' .initsubmitbtn').val(sbmtitle+' '+$('#'+uplform+' .uplnamefield').val()).show();
               
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context)
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index, file) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context)
                .append('<br>')
                .append(error);
        });
    })
}
function toggleObject(ix,cls,rix,prp) {
	if(!prp) var prp='checked';
	if(!rix) var rix=ix;
	var o=$('#'+ix); var r=$('#'+rix);
	o.prop(prp,!o.prop(prp));
	if (o.prop(prp)) r.addClass(cls);
	else r.removeClass(cls);
}
function pad(str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
function setNumericField(sel,dec) {
	if(!dec) var dec=false;
	$(sel).keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: .
            (e.keyCode == 190 && dec) || 
             // Allow: Ctrl+A Ctrl+C Ctrl+V Ctrl+X
            (e.ctrlKey === true && $.inArray(e.keyCode, [65, 67, 86,88])) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}
function onTabLoad(ui) {
	//alert(ui.panel.prop('id'));
	ix=ui.tab.prop('id').substr(4);
	if(ui.tab.attr('refresh')=='no') ui.tab.find('#tab_title_'+ix).prop('href','#'+ui.panel.prop('id'));
	ui.panel.find('[onTabLoad]').each(function(index, value){eval($(this).attr('onTabLoad'))})
	$('form').attr('autocomplete', 'off');
}
function onTabActivate(ui) {
	//alert(ui.panel.prop('id'));
	ix=ui.newTab.prop('id').substr(4);
	if(ui.newTab.attr('refresh')=='no') ui.newTab.find('#tab_title_'+ix).prop('href','#'+ui.newPanel.prop('id'));
	ui.newPanel.find('[onTabActivate]').each(function(index, value){eval($(this).attr('onTabActivate'))})
	$('form').attr('autocomplete', 'off');
}
function checkForm(f) {
	var a=new Array();
	$('#'+f+' input[requiredMsg]').each(function(index,event){
		if(!$(this).val()) {
			a.push($(this).attr('requiredMsg'));
			$(this).addClass('bgred').focus(function(){$(this).removeClass('bgred')});
		}
	})
	if(a.length) {
		alert(a.join("\n"));
		return false;
	} else return true;
}
function changeCMVar(id,ix,val) {
	var c=$("#"+id).attr("oncontextmenu");
	var cmv=c.split(',event');
	var n=cmv[1].lastIndexOf(')');
	if (n>0) var vA=cmv[1].substr(1,n-1).split(',');
	else vA=new Array();
	while(vA.length<ix) vA.push(0);
	vA[ix-1]=val;
	var newcm=cmv[0]+",event,"+vA.join(',')+")";
	$("#"+id).attr("oncontextmenu",newcm);
}
function setCMVars(id,vars) {
	var c=$("#"+id).attr("oncontextmenu");
	var cmv=c.split(',event');
	if(vars) var newcm=cmv[0]+",event,"+vars+")";
	else var newcm=cmv[0]+",event)";
	$("#"+id).attr("oncontextmenu",newcm);
}
function openPopup(lnk,param) {
	if(param) var prm=param+"&";
	else var prm='';
	window.open("rplc2.php?"+prm+"scr="+lnk,"_blank", "resizable: yes; help: no; status: no; scroll: no; ");
}
function getCheckTxt(frm,nm,sep) {
	if(!sep) sep='|';
	return	$('#'+frm+' input[name="'+nm+'[]"]:checked').map(
			function () {return this.value;}
			).get().join(sep);
}
function isEdit(o) {
	if(!o) var o=currCMOpt;
	if(!o) var o=pageopt;
	return $.inArray(o,edits)>-1?true:false;
}
function isView(o) {
	if(!o) var o=currCMOpt;
	if(!o) var o=pageopt;
	return $.inArray(o,views)>-1?true:false;
}
function getSQ(o) {
	aCMC(0,'specijalniupit','show','qopt='+o);
}
function getTblSettingsVar(t) {
	var v=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table='"+t+"'"],
				['getp','Spremi postavke tablice','tblwidths',"'table="+t+"&widths='+getTableWidths("+t+")"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table="+t+"'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('"+t+"')"]
		],t
	 ];
	 return v;	
}

function initTableHeaderJSVars(tbl) {
	eval(tbl+"_fields=''");
	eval(tbl+"_H={}");
	eval(tbl+"_W={}");
	eval(tbl+"_A={}");
	eval(tbl+"_T={}");
}
function initObjectJSVar(o){
	eval(o+"={}");
}
function defineCMfunc(name,tbl,cmvar) {
	eval ("if(typeof "+name+" !== 'function') "+name+"=function(i,c,e){doOnTblCM(i,c,e,"+tbl+",'"+cmvar+"');}");
}
function getTblSelectedVars(tbl,v) {
		var inselrows=false; var rArr=new Array();
	//	for (var i=0;i<tbl.selectedRows.length;i++) if (curid==tbl.selectedRows[i].idd) inselrows=true;
	//	return (inselrows ? tbl.getSelectedId() : curvar);	
		for (var i=0;i<tbl.selectedRows.length;i++) {
				var val=getRowCMVar(tbl,tbl.selectedRows[i].idd,v-1);
				if (val) rArr.push(val);
		}
		return (rArr.join("|"));
}
function InitStandardTable(tbl,lnk,cnt,cm,pref) {
		eval('setHeaderTable("'+tbl+'");');
		eval(tbl+'.setInitWidths(setTableCols("'+tbl+'","W"));');
		eval(tbl+'.setColAlign(setTableCols("'+tbl+'","A"));');
		eval(tbl+'.setColTypes(setTableCols("'+tbl+'","T"));');
		eval(tbl+'.enableMultiselect(true);');
		eval(tbl+'.attachEvent("onRightClick",'+cm+');');
		eval(tbl+'.attachEvent("onRowSelect",stdRowSelected);');
		eval(tbl+'.init();');
		eval('loadTable('+tbl+',"'+lnk+'",cnt,true,50,"'+pref+'");');
}
function toFixed2( num ) {
    return (Math.round( num * 100 ) / 100).toFixed(2);
}

function getTblCMInfoArray( tbl ) {
    return ['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert("+tbl+".selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert("+tbl+".getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('"+tbl+"')"]
			]];
}
function getTblHeaderCM(tbl,prs) {
	if(!prs) var prx='';
	else prx="+'&'+"+prs;
	return ['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table="+tbl+"'"+prx],
				['getp','Spremi postavke tablice','tblwidths',"'table="+tbl+"&widths='+getTableWidths("+tbl+")"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table="+tbl+"'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('"+tbl+"')"]
		],tbl
	 ];	  
}
function toggleProp(sel,pro) {
	$(sel).each(function() {
		$( this ).prop( pro,!$( this ).prop( pro) );
	})
}
function calcInputSum(sel) {
	var sum=0;
	$(sel).each(function() {
		sum+=$( this ).val()*1;
	})
	return sum;
}

function testGridID(g,rid) {
	eval("var grd="+g);
	return (grd && (grd.getRowIndex(rid)>-1))?true:false;

}


function addSelectedToSrcField(tbl,fld) {
	var ids=getSelectedIds(tbl,currentItem);
	if(ids) {
		var curr=$('#'+fld).val();
		if(curr) var cA=curr.split(',');
		else var cA=new Array();
		var nA=ids.split('|');
		for(var i=0;i<nA.length;i++) if (!inArray( nA[i],cA)) cA.push(nA[i]);
		$('#'+fld).val(cA.join(','));
	}
}
function xls(t,col,p,a,c,add,o) {
	if(!col) var col=1;
	if(!t) var t=pageopt;
	if(!add) var add='';
	if(!o) var o=pageopt;
	if(!a) var a=(pageact=='show')?'list':pageact;
	if(!p) var p=pagetype;
	if(!c) var c=$('#mainsurfacetable #cnt').html();

	if(add) add='&'+add;
	switch(col) {
		case 0: var tc=0; break;
		case 2: var tc=1; break;
		default: if(confirm("Želite li u excel datoteci uključiti i bojanje redova?")) var tc=1; else var tc=0;
	}
	new_tab('xls.php?tbl='+t+'&opt='+o+'&act='+a+'&pagetype='+p+'&count='+c+'&showTableColors='+tc+add);
}