   
function makePostContent(g_formname) {
	g_content='';
	var reo=new RegExp("&","g");
	var reo2=new RegExp("[+]","g");
	var g_form=document.getElementById(g_formname);
			for(i=0;i<g_form.elements.length;i++) {
				var g_pomel=g_form.elements[i];
				switch (g_pomel.type) {
					case "radio": case "checkbox": if (g_pomel.checked) g_content+=(g_pomel.name+'='+g_pomel.value+"&");
						break;
					case undefined: case 'fieldset': break;
					default:
					var v=g_pomel.value.replace(reo,"%$#");
					v=v.replace(reo2,'*7*8*7*6*');
					g_content+=(g_pomel.name+'='+v+"&");
					//g_content+=(g_pomel.name+'='+g_pomel.value+"&");
				}
				//alert(g_pomel.name+':'+g_pomel.id+'='+g_pomel.value.replace(reo,"%$#")+"&");
			}
		return g_content;
}

					  
function g_Send(urlx,meth,frmname,extra) {
	if (!extra) extra='';
	var isPOST=(meth && (meth!='GET')) ? true : false;
    var url=simConfig_live_site+'/rplc.php?'+urlx;
	if (jah_debug0) alert(urlx);
	var cont='';
	if (isPOST) {
		if (frmname=='ordering') cont=extra;
		else cont=makePostContent(frmname)+extra;
	}
	
	 if (window.XMLHttpRequest) req = new XMLHttpRequest();
	 else if (window.ActiveXObject) 
        req = new ActiveXObject("Microsoft.XMLHTTP");
	
	if (req) {
         req.onreadystatechange = function() {
			g_Done();
		};
        req.open("POST", url, true);
		document.body.style.cursor="wait";
		if (isPOST) {
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			req.setRequestHeader('Encoding', 'utf-8');
			req.send(cont);
		} else   if (window.XMLHttpRequest) req.send(null);
		else if (window.ActiveXObject) req.send();
	} else alert('Request not defined');
   
}    

function JAH_action(_acttext) {
	var g_act=_acttext.split('?#?');
	var g_head=g_act[0].split('|#|');
	this.mainblock=TrimString(g_head[0].toLowerCase());
	this.target=TrimString(g_head[1].toLowerCase());
	this.command=TrimString(g_head[2].toLowerCase());
	this.position=TrimString(g_head[3].toLowerCase());
	this.block=new Array();
	if (g_act.length>1) for(gi=1; gi<g_act.length; gi++) {
		_tmpblock=new JAH_block(g_act[gi]);
		this.block.push(_tmpblock);
	}
}
function JAH_block(_bltext) {
	var g_block=_bltext.split('|#|');
	this.type=TrimString(g_block[0].toLowerCase());
	this.content=g_block[1];
}
function g_Done() {
	 document.body.style.cursor="default";
	 if (req.readyState == 4) {
        if (req.status == 200) {
			var resText = req.responseText;
			if (jah_debug1)	showDebugger(resText);
			if (resText) {
				var g_actions=resText.split('+#+');
				var actions=new Array();
				for (gx=0;gx<g_actions.length;gx++) {
					_act=new JAH_action(g_actions[gx]);
					actions.push(_act);
					switch (_act.command) {
						case 'after': g_retx=g_After(_act); break;
						case 'before': g_retx=g_Before(_act); break;
						case 'insert': g_retx=g_Insert(_act); break;
						case 'insertfull': g_retx=g_InsertFull(_act); break;
						case 'append': g_retx=g_Append(_act); break;
						case 'replace': g_retx=g_Replace(_act); break;
						case 'change': g_retx=g_Change(_act); break;
						case 'changeinside': g_retx=g_ChangeInside(_act); break;
						case 'changeselectbox': g_retx=g_ChangeSelectBox(_act); break;
						case 'suggest': g_retx=g_Suggest(_act); break;
						case 'insertrow': g_retx=g_InsertRow(_act); break;
						case 'changerow': g_retx=g_ChangeRow(_act); break;
						case 'delete': g_retx=g_Delete(_act); break;
						case 'alert': g_retx=g_Alert(_act); break;
						case 'confirm': g_retx=g_Confirm(_act); break;
						case 'simalert': g_retx=g_simAlert(_act); break;
						case 'alertok': g_retx=g_AlertOK(_act); break;
						default: g_retx=g_Javascript(_act);
					}
					if (!g_retx) alert("Došlo je do greške u skripti.\n Refrešajte stranicu i pokušajte ponovo!");
				}
				if (window.popw) window.popw.close();
			}
        } else {
            alert("jah error:\n" +
                req.statusText);
        }
    }
}

function g_Insert(_a,g_type){
	if (!g_type) g_type='div';
	var g_main=document.getElementById(_a.mainblock);
	var newel=document.createElement(g_type);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": newel.innerHTML=_a.block[ix].content;
						 newel.id=_a.target;	
						 var xx=g_main.appendChild(newel);	
						  if (xx.attributes.getNamedItem('class')) {
									xx.className=xx.attributes.getNamedItem('class').value;
							}
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_After(_a){

	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": $('#'+_a.target).after(_a.block[ix].content);
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_Before(_a){
	var g_main=document.getElementById(_a.mainblock);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": $('#'+_a.mainblock).before(_a.block[ix].content);
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_InsertFull(_a,g_type){
	if (!g_type) g_type='div';
	var g_main=document.getElementById(_a.mainblock);
	var pomel=document.createElement('div');
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": pomel.innerHTML=_a.block[ix].content;
						 for(var i=0;i<pomel.childNodes.length;i++) if (pomel.childNodes[i].id==_a.target) {
							 var contel=pomel.childNodes[i];						  
							 var newel=g_main.appendChild(contel);	
							 if (newel.attributes.getNamedItem('class')) {
									newel.className=newel.attributes.getNamedItem('class').value;
							}
						 }
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_Append(_a){
	var g_main=document.getElementById(_a.mainblock);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": if (_a.position==1)  g_main.innerHTML=_a.block[ix].content+g_main.innerHTML;
						 else g_main.innerHTML=g_main.innerHTML+_a.block[ix].content;
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_Change(_a){
	var newel=document.getElementById(_a.target);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": if (newel) {	
						while(newel.childNodes.length>0) newel.removeChild(newel.childNodes[0]);
						newel.innerHTML=_a.block[ix].content; 
						if (newel.attributes.getNamedItem('class')) {
							newel.className=newel.attributes.getNamedItem('class').value;
						}
					}
					break;
			case "select": if (newel && (newel.type.substr(0,6)=="select")) {
				var rXMLo=makeXMLFromString(_a.block[ix].content);
				clearSelectOptions(newel.id);
				for(var i=0; i<rXMLo.childNodes.length; i++) if (rXMLo.childNodes[i].nodeName.toLowerCase()=='option'){
						var op=rXMLo.childNodes[i];
						var nop=document.createElement('option');
						nop.value=op.attributes.getNamedItem('value').value;
						nop.text=op.childNodes[0].nodeValue;
						newel.add(nop);
				}
				//newel.innerHTML=_a.block[ix].content; 
			}
				break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}
function g_ChangeSelectBox(_a){
	var newel=document.getElementById(_a.target);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": if (newel && (newel.type.substr(0,6)=="select")) {
							var rXMLo=makeXMLFromString(_a.block[ix].content);
							clearSelectOptions(newel.id);
							for(var i=0; i<rXMLo.childNodes.length; i++) if (rXMLo.childNodes[i].nodeName.toLowerCase()=='option'){
									var op=rXMLo.childNodes[i];
									var nop=document.createElement('option');
									nop.value=op.attributes.getNamedItem('value').value;
									nop.text=op.childNodes[0].nodeValue;
									if (op.attributes.getNamedItem('selected')) nop.selected=true;
									newel.add(nop);
							}
						 }
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}

function g_Suggest(_a){
	clearSuggestion();
	var field=document.getElementById(_a.target);
	var search_suggest=document.getElementById('search_suggest');
	search_suggest.innerHTML='';
	search_suggest.style.height="auto";
	search_suggest.style.width="auto";
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": 	var rXMLo=makeXMLFromString(_a.block[ix].content);
							var cn_x=0;
							for(var i=0; i<rXMLo.childNodes.length; i++) if (rXMLo.childNodes[i].nodeName.toLowerCase()=='suggest'){
									var op=rXMLo.childNodes[i];
									var nop=document.createElement('div');
									nop.id='ss_'+op.attributes.getNamedItem('id').value;
									nop.innerHTML=op.childNodes[0].nodeValue.replace("&amp;","&");
									//nop.innerHTML=op.childNodes[0].nodeValue;
									nop.className='suggest_link';
									var p=search_suggest.appendChild(nop);
									attachSuggestFunctions(p,op,_a.target);
									cn_x++;
									
							}
							if (cn_x>0) {
								detachFunction(field,'keydown',selectSuggestion);
								showUnderElement('search_suggest',_a.target);
								attachFunction(field,'blur',function () {setTimeout("hideStandardPopup('search_suggest');",250)});
								attachFunction(field,'keydown',selectSuggestion);
								var ot=getY(search_suggest);
								if (isNN) var ch=window.innerHeight; else  var ch=document.body.clientHeight;
								if (ot>(ch-search_suggest.offsetHeight)) {
									search_suggest.style.height=1*(ch-ot-5)+"px";
									search_suggest.style.width=(search_suggest.offsetWidth+25)*1+"px";
								}
								else {
									search_suggest.style.height="auto";
									search_suggest.style.width="auto";
								}
								
							} else hideStandardPopup('search_suggest'); 													 
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}

function g_InsertRow(_a,g_type){
	
	
	var g_main=document.getElementById(_a.mainblock);
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont":
						
						 break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}

function g_ChangeInside(_a){
	var newel=document.getElementById(_a.target);
	var pomel=document.createElement('div');
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": if (newel) {	
						while(newel.childNodes.length>0) newel.removeChild(newel.childNodes[0]);
						pomel.innerHTML=_a.block[ix].content; 
						for(var i=0;i<pomel.childNodes.length;i++) if (pomel.childNodes[i].id==_a.target) {
							 var contel=pomel.childNodes[i];
							 for(var j=0;j<contel.childNodes.length;j++)
								 newel.appendChild(contel.childNodes[j]); 							
						}						
					}
					break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	return true;
}

function g_Replace(_a){
	var newel=document.getElementById(_a.target);
	var pomel=document.createElement('div');
	for (ix=0;ix<_a.block.length;ix++) {
		switch(_a.block[ix].type) {
			case "cont": 
					while(newel.childNodes.length>0) newel.removeChild(newel.childNodes[0]);
					pomel.innerHTML=_a.block[ix].content;
					for(var i=0;i<pomel.childNodes.length;i++) if (pomel.childNodes[i].id==_a.target) var contel=pomel.childNodes[i];
					newel.innerHTML=contel.innerHTML; 
					
					if (contel.attributes.getNamedItem('class')) {
						newel.className=contel.attributes.getNamedItem('class').value;
					}
					//if (contel.attributes.getNamedItem('oncontextmenu')) {
					//	newel.className=contel.attributes.getNamedItem('oncontextmenu').value;
					//}
					break;
		    case "jscr": eval(_a.block[ix].content); break;
			default: alert(_a.block[ix].content);
		}
	}
	
	return true;
}

function g_Delete(_a){
	var newel=document.getElementById(_a.target);
	if (newel) {
		var par=newel.parentNode;
		par.removeChild(newel);
		for (ix=0;ix<_a.block.length;ix++) alert(_a.block[ix].content);		
	} return true;
}
function g_Alert(_a){
	for (ix=0;ix<_a.block.length;ix++) alert(_a.block[ix].content);
	return true;
}
function g_simAlert(_a){
	for (ix=0;ix<_a.block.length;ix++) simAlert(_a.block[ix].content);
	return true;
}
function g_AlertOK(_a){
	$('#okalert').fadeIn(500,function() { $('#okalert').fadeOut(1000) });
	playFx('rattle');
	return true;
}
function g_Javascript(_a){
	
	for (ix=0;ix<_a.block.length;ix++)  eval(_a.block[ix].content);
	return true;
}
function g_Confirm(_a){
	
	if(_a.block.length==3) {
		if(confirm(_a.block[0].content)) eval(_a.block[1].content);
		else if (a.block[2].content) eval(_a.block[2].content);
	}
	return true;
}
function TrimString(sInString) {
  sInString = sInString.replace( /^\s+/g, "" );// strip leading
  return sInString.replace( /\s+$/g, "" );// strip trailing
}

function makeXMLFromString(text) {
	var xmlt='<headelement>'+text+'</headelement>';
	if (window.ActiveXObject)  {
		  var doc=new ActiveXObject("Microsoft.XMLDOM");
		  doc.async="false";
		  doc.loadXML(xmlt);
	 }	else  {
		  var parser=new DOMParser();
		  var doc=parser.parseFromString(xmlt,"text/xml");
	 }
	 return doc.documentElement;
}