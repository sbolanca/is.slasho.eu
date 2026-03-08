isIE=document.all;
isNN=!document.all&&document.getElementById;
isN4=document.layers;

var cnfResultVar=0;
var cnffunction=null;

if (isIE||isNN) {
	document.oncontextmenu=blockRightClick;
} else {
	document.captureEvents(Event.MOUSEDOWN || Event.MOUSEUP);
	document.onmousedown=blockRightClick;
}

function blockRightClick(e) {
	if (!e) e=event;
	if (isN4) {
		if (e.which==2||e.which==3) {
			return false;
		}
	}
	//else return false;
	else return e.ctrlKey;
}

function attachFunction(el,ev,func) {
	if (document.all) el.attachEvent('on'+ev, func );
	else  {
		el.addEventListener(ev, func, false);
	}
}
function attachPlusFunction(el) {
	if (el.className!="tpem") {
		tmpf=function() {
			expandCollapseF(el.id,false,false);
		}
		attachFunction(el,'click',tmpf);
	}
}
	
	var cmiid=0;
function getOPT(a,d) {
	var aA=a.split('|');
	if (aA.length>1) return aA[0];
	else return d;
}
function getACT(a) {
	var aA=a.split('|');
	if (aA.length>1) return aA[1];
	else return a;
}

function createMenuItem(cmx,cmi,opti,tbltmp,isSub) {
	var cm=cmx.lastChild;
	var tmp=document.createElement('li');
	var titl=cmi[1];
	if (titl.substr(0,4)=='exe:') titl=eval(titl.substr(4));
	if(titl.indexOf("|")>0) {
		shortcut=titl.substr(0,titl.indexOf("|"));
		titl=titl.substr(titl.indexOf("|")+1);
	} else shortcut='';
	var tel=document.createTextNode(titl);
	tmp.appendChild(tel);
	if (shortcut) {

		var sht = document.createElement('span');
		sht.innerHTML=shortcut;
		sht.className = "shortcut";
		tmp.appendChild(sht);
	}
	cm.appendChild(tmp);
	var trigger='click';
	if (cmi[0]=='dsbl') {
		tmp.style.color="#999999";
//		tmp.style.fontStyle="italic";
		return false;
	} else {
		tmp.id="cmi_"+cmiid++;
		var cmi0=cmi[0].split('-');
		switch(cmi0[0]) {
			case 'get':case 'getp': var func=function() {
							if (CMTest(cmi,4)) eval(cmi[4]);
							if (CMTest(cmi,3)) eval('var varsx='+cmi[3]); else var varsx='';
							if (cmi0[0]=='get') activateCMCommand(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);			
								else activateCMCommandGETP(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);	
						}; break;
			case 'del':  case 'delp': var func=function() {
							if (CMTest(cmi,5)) eval(cmi[5]);
							if (CMTest(cmi,4)) eval('var varsx='+cmi[4]); else var varsx='';
							var pommsgconfirmed=1;
							if ((cmi.length>3) && cmi[3]) {
								var pommsg=cmi[3];
								if (pommsg.substr(0,4)=='exe:') pommsg=eval(pommsg.substr(4));
								if (!confirm(pommsg)) pommsgconfirmed=0;
							}
							if (pommsgconfirmed) {
								if (cmi0[0]=='del') activateCMCommand(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);			
								else activateCMCommandGETP(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);	
							}			
						}; break;
			case 'sel': case 'chk': case 'selp': case 'chkp': var func=function() {
								var pommsg=cmi[3];
								
								cnffunction=function() {
									if (CMTest(cmi,6)) eval(cmi[5]);
									if (CMTest(cmi,5)) eval('var varsx='+cmi[5]); else var varsx='';
									if (cmi0[0].substr(0,3)=='chk')  eval('var cnfres="selectedactions="+cnfResultVar;');
									else eval('var cnfres="selectedaction="+cnfResultVar;');								 
									if (varsx) varsx+='&'+cnfres;
									else varsx=cnfres;
									if (cmi0[0].length==4) activateCMCommandGETP(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);			
									else activateCMCommand(getOPT(cmi[2],opti),getACT(cmi[2]),varsx);	
									//alert("activateCMCommand('"+opti+"','"+cmi[2]+"','"+varsx+"'");
								}
								showAdminForm(titl,pommsg,cmi[4],cmi0[0].substr(0,3));
								
											
						}; break;
			case 'ord': var func=function() {
							if (CMTest(cmi,5)) eval(cmi[5]);
							if (CMTest(cmi,4)) eval('var varsx='+cmi[4]); else var varsx='';
							if (cmi[3].substr(0,1)=="'")
							eval('var contx='+cmi[3]);
							else var contx=cmi[3];
							var pom = $( "#"+contx ).sortable( "serialize" );
							//alert (pom);
							activateCMCommandORD(getOPT(cmi[2],opti),getACT(cmi[2]),pom,varsx); 
						}; break;
			
		case 'img': var func=function() {
							if (CMTest(cmi,4)) eval(cmi[4]);
							if (cmi[3].substr(0,7)=="current")
								eval('var mdl='+cmi[3]);
							else var mdl=CMVal(cmi,3,0);
							var idx=CMVal(cmi,6,0);
							if (idx) idx+=currentItem;
							var fldx=CMVal(cmi,5,'');
							if (fldx) eval('var fldx='+cmi[5]);
							changeIm(idx,cmi[2],mdl,fldx);
						}; break;
			case 'fil': var func=function() {
							if (CMTest(cmi,3)) eval(cmi[3]);
							var idx=CMVal(cmi,5,0);
							var fldx=CMVal(cmi,4,'');
							changeFile(idx,cmi[2],fldx);
						}; break;
			case 'sub':  var func=function() { createSubMenu(opti,cmi[2],tbltmp,tmp.id);}; 
						 trigger="mouseover"; 
						 tmp.style.fontWeight = "bold";
						 tmp.style.backgroundImage = "url(images/ar.gif)";
						 tmp.style.backgroundRepeat = "no-repeat";
						 tmp.style.backgroundPosition = "center right";
						// tmp.style.color = "#FF9999";
						 break;
			default: var func=function() {
							eval(cmi[2]);
						}; break;
	
		}
		attachFunction(tmp,trigger, func);
		if (!isSub && !(cmi0[0]=='sub')) attachFunction(tmp,'mouseover', function(){hideContextMenu('subcontextmenu')});
		return true;
	} 
}
function CMVal(arr,ix,var0) {
	if ((arr.length>ix) && arr[ix]) var idx=arr[ix]; else var idx=var0;
	return idx;
}
function CMTest(arr,ix) {
	if ((arr.length>ix) && arr[ix]) return true;
	else return false;
}
 
function createSeparatorItem(cm,c) {
	var nex=document.createElement('ul')
	if (c) nex.className="cm"+c;
	cm.appendChild(nex);
	return nex;
}
function testCMAppering(cmi,tbltmp) {
	var ret=new Array();
	for (var i=0;i<cmi.length;i++) ret.push(cmi[i]);
	var dsbl=false;
	var cmi0=cmi[0].split('-');
	if (tbltmp && (cmi0.length>1)) {
		
		switch (cmi0[1]) {
			case '1': if (tbltmp.selectedRows.length>1) dsbl=true; break;
			case '2': if (!(tbltmp.selectedRows.length==2)) dsbl=true; break;
			case 'N': if (tbltmp.selectedRows.length==1) dsbl=true; break;
		}
	}
	
	if(dsbl) switch(cmi0[0]) {
		case 'ord' : case 'test' : ret=false; break;
		case 'testx' : ret=ret.slice(2);
		default: ret[0]='dsbl'; 
	} else switch(cmi0[0]) {
		
	// switch(cmi[0]) {
			case 'ord': 
				if ((cmi[3].substr(0,1)=='"') || (cmi[3].substr(0,1)=="'")) eval('var cn='+cmi[3]);
				else eval("var cn='"+cmi[3]+"'");
				var container=document.getElementById(cn);
					if (!container.children.length) ret=false;
					break;
			case 'sub': var subcnt=0;
						for (var i=0; i<cmi[2].length;i++) if (!(cmi[2][i][0]=='sep')) {
							var pom=testCMAppering(cmi[2][i],tbltmp);
							if (pom && !(pom[0]=='dsbl')) subcnt++; 
						}
						if (!subcnt) ret[0]='dsbl'; 
						 break;
			case 'test': if (makeCMITest(cmi[1],tbltmp)) ret=cmi.slice(2); 
						 else ret=false;
						 break;
			case 'testx': var cn=makeCMITest(cmi[1],tbltmp);
						 ret=cmi.slice(2); 
						 if (!cn) ret[0]='dsbl';
						 break;
	}
	return ret;
}

function makeCMITest(tst,tbltmp) {
	if (typeof tst=='string') {
		if (tbltmp && (tbltmp.selectedRows.length>1)) {
			var reg0=new RegExp("currentItem","g");
			var regs=new Array();
			var selrowcm=getCMVarsTblArray(currentItem,tbltmp);
			for (var j=0;j<selrowcm.length;j++) regs.push(new RegExp("currentVar"+(j+1),"g"));
			var cn=true; var i=0;
			while((i<tbltmp.selectedRows.length) && Number(cn)) {
				var tstx=tst.replace(reg0,tbltmp.selectedRows[i].idd);
				if (selrowcm.length>0) {
						var rowcm=getCMVarsTblArray(tbltmp.selectedRows[i].idd,tbltmp);
						for (var j=0;j<rowcm.length;j++) tstx=tstx.replace(regs[j],rowcm[j]);
				}
				eval('var cn='+tstx);
				
				i++;
			}
		} else  eval('var cn='+tst);
	}  else var cn=tst;
	return cn;
}
function createSubMenu(opti,cmiArr,tbltmp,parnt) {
	var cms=document.getElementById('subcontextmenu');
	cms.innerHTML='';
	var frs=document.createElement('ul');
	cms.appendChild(frs);
	var showCM=false;
	var lastSep=false;
	var lastSepClass='';
	var cntEnabled=0;
	for(var i=0;i<cmiArr.length;i++) if (cmiArr[i][0]=='sep') {
		if ((i>0) && !(lastSep)) { lastSep=true; lastSepClass=(cmiArr[i][1]) ? cmiArr[i][1] : ''; }
	} else if (cmi=testCMAppering(cmiArr[i],tbltmp)) {
		if (lastSep) createSeparatorItem(cms,lastSepClass);
		if (createMenuItem(cms,cmi,opti,tbltmp,true)) cntEnabled++;
		showCM=true; lastSep=false;
	}
	//if (!cntEnabled) document.getElementById(parnt).style.color="#999999";
	if(showCM) {
		createCMMenu(cms);
		showSubContextMenu(parnt);
	} else hideContextMenu('subcontextmenu');
	return cntEnabled;
}
CMactionScriptLoaded=new Array();
function cCM(id,cmObjTitle,evt) {
  if (!evt) evt = window.event; 
  if(!evt.ctrlKey) {
	if (arguments.length>3) for(var i=3;i<arguments.length;i++) {
		eval('currentVar'+(i-2)+'=arguments['+i+'];');
	}
	
	if(window[cmObjTitle] == undefined) {	
		
		var ct=cmObjTitle.split("_");
		if(ct[0]=='CM') {
			switch(ct[1]) {
				case 'opt': var scrcode='opt_'+ct[2]; var scr="opt/"+ct[2]+"/js/cm_actions.js"; break;
				case 'mod': var scrcode='mod_'+ct[2]; var scr= "mod/mod_"+ct[2]+"/cm_actions.js"; break;
				default: var scrcode= ""; 
			}
		
			if (scrcode && ($.inArray(scrcode,CMactionScriptLoaded)<0)) {
					$('html,body').css('cursor','wait');
					$.getScript(scr).done(function( script, textStatus ) {
						$('html,body').css('cursor','default');
						CMactionScriptLoaded.push(scrcode);
						eval('cCMgo(id,'+cmObjTitle+',evt)');	
					  })
					  .fail(function( jqxhr, settings, exception ) {
						$('html,body').css('cursor','default');
						alert(exception);
						if(jqxhr.status!=200) alert('Greška kod uèitavanja cm_actions: '+cmObjTitle+'\n - '+scrcode+'\n - '+scr+'\nSTATUS: '+jqxhr.status);
						else eval('cCMgo(id,'+cmObjTitle+',evt)');							
					});
			} else alert('Nepoznat tip cm_actions skripte: '+cmObjTitle);
		} else alert('Nepoznat tip cm_actions skripte: '+cmObjTitle);
	} else eval('cCMgo(id,'+cmObjTitle+',evt)');
  } else return true;
}
currCMOpt='';
function cCMgo(id,cmObj,evt) {
	var showCM=true;
	currCMOpt=cmObj[0];
	if (cmObj.length>3) showCM=makeCMITest(cmObj[3]);
	if (showCM) {
		currentItem=id;
	
		var cm=document.getElementById('contextmenu');
		cm.innerHTML='';
		var frs=document.createElement('ul');
		cm.appendChild(frs);
		var showCM=false;
		cmiid=0;
		var lastSep=false;
		var lastSepClass='';
		if ((cmObj.length>2) && cmObj[2]) { eval("var tbltmp="+cmObj[2]+";"); }
		else var tbltmp=false;
		for(var i=0;i<cmObj[1].length;i++) if (cmObj[1][i][0]=='sep') {
			if ((i>0) && !(lastSep)) { lastSep=true; lastSepClass=(cmObj[1][i][1]) ? cmObj[1][i][1] : ''; }
		} else if (cmi=testCMAppering(cmObj[1][i],tbltmp)) {
			//if (cmObj[1]) VannaScriptLoader.AddScript('opt/'+cmObj[0]+'/lang/'+alang+'.js');
			if (lastSep) createSeparatorItem(cm,lastSepClass);
			createMenuItem(cm,cmi,cmObj[0],tbltmp);
			showCM=true; lastSep=false;
		}
	}
	if(showCM) {
		
		createCMMenu(cm);
		if (!evt) evt = window.event; 
		showContextMenu(evt);
	} else hideContextMenu();
}
function createCMMenu(cm) {
		var maxw=0;
		for(var j=0;j<cm.childNodes.length;j++) if(cm.childNodes[j].nodeName.toLowerCase()=='ul') {
			var ul=cm.childNodes[j];
			for(var i=0;i<ul.childNodes.length;i++)
				if (ul.childNodes[i].nodeName.toLowerCase()=='li') {
					if (ul.childNodes[i].id) attachContextMenuFunction(ul.childNodes[i]);
					maxw=Math.max(ul.childNodes[i].offsetWidth,maxw);
				}
		}
		for(var j=0;j<cm.childNodes.length;j++) if(cm.childNodes[j].nodeName.toLowerCase()=='ul') {
			var ul=cm.childNodes[j];
			for(var i=0;i<ul.childNodes.length;i++)
				if (ul.childNodes[i].nodeName.toLowerCase()=='li') ul.childNodes[i].style.width=parseInt(maxw+5)+' px';
		}
}

function makeArrayCM(m,o,ar,a,v) {
	var arrcm=new Array();
	for (var i=0;i<ar.length;i++) {
		var cm_it=[m,ar[i],a,'"'+v+'='+ar[i]+'"'];
		arrcm.push(cm_it);
	}
	var ret=[o,'',arrcm];
	return ret;
}
function makeArrayCMID(m,o,ar,a,v) {
	var arrcm=new Array();
	for (var i=0;i<ar.length;i++) {
		var spl=ar[i].split('|');
		var cm_it=[m,spl[1],a,'"'+v+'='+spl[0]+'"'];
		arrcm.push(cm_it);
	}
	var ret=[o,'',arrcm];
	return ret;
}
function makeCMItemsLangID(m,l,ar,a,v) {
	var arrcm=new Array();
	for (var i=0;i<ar.length;i++) {
		var cm_it=[m,l+ar[i].toLowerCase(),a,v+'='+ar[i]+"'"];
		arrcm.push(cm_it);
	}
	return arrcm;
}
function makeCMItemsLangIDComplex(m,l,ar,a,v) {
	var arrcm=new Array();
	for (var i=0;i<ar.length;i++) {
		var spl=ar[i].split('|');
		var cm_it=[m,l+'-'+spl[1],a,v+'='+spl[0]+"'"];
		arrcm.push(cm_it);
	}
	return arrcm;
}
function MergeCM(cmname,cm2name) {
	eval('var pom='+cmname+'[2].concat('+cm2name+'[2]);');
	eval('cmname[2]=pom;');
}

function attachContextMenuFunction(li) {
			var f1=function() {highlightMenu(li,true); }
			var f2=function() {highlightMenu(li,false); }
			
			attachFunction(li,'mouseover',f1);
			attachFunction(li,'mouseout', f2);
			attachFunction(li,'click', hideContextMenu);
}
function highlightMenu(el,sel) {
	waitForMenu=sel;
	if (sel) {
		el.style.backgroundColor="#AB290F";
		el.style.color="white";
		$('#'+el.id+' .shortcut').css('color','white');
	} else {
		el.style.backgroundColor="";
		el.style.color="black";
		$('#'+el.id+' .shortcut').css('color','#2222AA');
	}
}


// no changes required below this line



function showContextMenu(evt) {
	showAtCursor('contextmenu',evt);		
}
function showSubContextMenu(target) {
	showNearElement('subcontextmenu',target,2);		
}

function hideContextMenu(men) {
	if (!men || !((typeof men=='string')) ) var men='contextmenu';
	var obj = document.getElementById(men).style; 
	obj.visibility = 'hidden'; 
}




//-----------------------------------

function hideContext() {
	hideContextMenu();
	hideContextMenu('subcontextmenu');
}

var waitForMenu=false;

attachFunction(document,'click',hideContext);

