var lconst = {
	ls_load : 'Loading images list...',
	ls_thumb : 'Creating thumbnail...',
	ls_upload : 'Uploading image...',
	ls_loadimage : 'Loading image...'
}

function actionx(a,m) {
	if (!m) m='load';
	var forma = document.getElementById('form1');
	forma.act.value=a;
	changeLoadingStatus(m);
	if (a=='upload') forma.submit();
	else submitforma2();
	//forma.submit();
}
function submitforma2() {
	var forma1 = document.getElementById('form1');
	var forma2 = document.getElementById('form2');
	for (var i=0;i<forma2.elements.length;i++) {
		var elem2=forma2.elements[i];
		elem1=document.getElementById(elem2.name);
		var val='';
		switch(elem1.type) {
			case "checkbox": case "radio": if (elem1.checked) val=elem1.value; break;
			case "select": case "select-one": val=elem1.options[elem1.selectedIndex].value;  break;
			default: val=elem1.value;
		}
		elem2.value=val;
	}
	forma2.submit();
}
var imbox='';
var imvalue='';
function Init() {
	imbox=opener.imbox;
	imvalue=opener.imvalue;

    var elmSelectedImage;
    var htmlSelectionControl = "Control";

    document.body.onkeypress = _CloseOnEsc;

    document.getElementById("url").fImageLoaded = false;
	   
  //  document.getElementById("f_url").value = globalDoc.adminForm.file_location.value;  // fix placeholder src values that editor converted to abs paths
                

    document.getElementById("url").value = (document.getElementById("url").value) || "http://";

}

function extractImgPath(u) {
	return u.replace(window.location.protocol+"//"+window.location.hostname,'');
}

function onOK(m) {
	
	if (CKE) opener.CKEDITOR.tools.callFunction(funcNum, extractImgPath(document.getElementById("url").value), '');
	else opener.doit(document.getElementById("url").value,imbox,imvalue,document.getElementById("resize").checked ? document.getElementById("resizevalue").value :0,m);
   window.close();
}



function _CloseOnEsc() {
    if(event.keyCode == 27) {
        window.close();
        return;
    }
}

function wclose() {
	window.close();
}
function onCancel() {
  return false;
};


function pviiClassNew(obj, new_style) { 
  obj.className=new_style;
}




var wlimit=419; var hlimit=359;
var  w=1;  var h=1;
var omjer=1; var comjer=1; var contx=1;var conty=1;
var rszW=1; var rszH=1; var rszOmjer=1;
function showImage(fajl) {
	if (!fajl && document.getElementById("url").value) fajl=document.getElementById("url").value;
	resetCroping();
	//document.imgPreview.src="ImageManager/image.php?imagepath=" + fajl;
	//window.open("image.php?imagepath=" + fajl,"imgPreview","");
	var cont=document.getElementById('imgPreview');

		//$filename=substr($imagepath,1+strrpos($imagepath,"/"));
		//else $imagepath="ImageManager/noimage.png";
	var ww=Math.max(document.getElementById('f_width').innerHTML*1,1);
	var hh=Math.max(document.getElementById('f_height').innerHTML*1,1);
	
	if (((ww/wlimit)>1) || ((hh/hlimit)>1)) {
		if ((ww/wlimit)>(hh/hlimit)) { w=wlimit; h=hh*wlimit/ww; }
		else { w=ww*hlimit/hh; h=hlimit; }
	}
	else {w=ww; 	h=hh;	}
	omjer=w/h;
	rszW=Math.round(w);
	rszH=Math.round(h);
	rszOmjer=rszW/ww;
	cont.innerHTML='<img id="mainImage" src="'+fajl+'" width="'+rszW+'" height="'+rszH+'" onLoad="layerVis(\'loading\',\'hidden\');"/>';
	var imp=document.getElementById('mainImage'); 
	contx=parseInt(getX( imp ));
	conty=parseInt(getY( imp ));
	

	//window.open("image.php?subfolder="+def_subfolder+"&imagepath=" + fajl,"imgPreview","");
}
var	cw=0;
var	ch=0;
function resetCroping() {
	w=1;  h=1;
	omjer=1; comjer=1; contx=1; conty=1;
	rszW=1; rszH=1; rszOmjer=1;
	cropDragging=false;
	mouseoffs=0;
	document.getElementById('offsetX').value="-1";
	document.getElementById('offsetY').value="-1";
	document.getElementById('cropbox').style.display="none";
}
function doCrop() {
	var cb=document.getElementById('cropbox');
	var ww=Math.max(1,document.getElementById('thumbWidth').value*1);
	var hh=Math.max(1,document.getElementById('thumbHeight').value*1)
	comjer=ww/hh;
	if (omjer>comjer) {
		ch=h;
		cw=ww*h/hh;
		cb.style.top=conty+"px";
		cb.style.left=Math.round(contx+(w-cw-3)/2)+"px";
	} else {
		cw=w;
		ch=hh*w/ww;
		cb.style.left=contx+"px";
		cb.style.top=Math.round(conty+(h-ch-3)/2)+"px";
	}
	
	cb.style.width=Math.round(cw-3)+"px";
	cb.style.height=Math.round(ch-3)+"px";
	cb.style.display="block";
	CStopDrag();
	//actionx('crop','crop');
}
var cropDragging=false;
var mouseoffs=0;
function CStartDrag(evt) {
	cropDragging=true;
	var imp = document.getElementById('cropbox'); 
	if (omjer>comjer) mouseoffs=parseInt(mouseX(evt))-parseInt(getX( imp ));
	else mouseoffs=parseInt(mouseY(evt))-parseInt(getY( imp ));
}
function CStopDrag(evt,dstop) {
	if (cropDragging) {
		if (!dstop) cropDragging=false;
		var imp = document.getElementById('cropbox');
		var ww=Math.max(document.getElementById('f_width').innerHTML*1,1);
		var hh=Math.max(document.getElementById('f_height').innerHTML*1,1);
		var xoffset=parseInt(getX( imp ))-contx;
		var yoffset=parseInt(getY( imp ))-conty;
		document.getElementById('offsetX').value=xoffset/rszOmjer;
		document.getElementById('offsetY').value=yoffset/rszOmjer;
		
	}
}
function CDrag(evt) {
	if (cropDragging && document.getElementById) {
		var obj = document.getElementById('cropbox').style; 
		if (omjer>comjer) obj.left = Math.min((Math.max(contx,parseInt(mouseX(evt))-mouseoffs)),contx+w-cw) + 'px';
		else obj.top = Math.min(Math.max(conty,(parseInt(mouseY(evt))-mouseoffs)),conty+h-ch) + 'px';
		
	}
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

function getObjectProp(id,p) { 
	var o=document.getElementById(id);	var v=null;	eval('v=o.'+p);
	return v;
}
function setObjectProp(id,p,v) { 
	var o=document.getElementById(id);	
	if (typeof v=='string') eval('o.'+p+'="'+v+'";');
	else eval('o.'+p+'='+v+';');
}

function layerVis(l,s) { 
	setObjectProp(l,'style.visibility',s);
}

function changeLoadingStatus(state) {
	var st='';
	eval ("st=lconst.ls_"+state+";");
	setObjectProp('loadingStatus','innerHTML',st);
	layerVis('loading','visible')
}

function goUpDir() {
	var pard=document.getElementById('parentDir');
	var dp=document.getElementById('dirPath');
	var dir = dp.options[dp.selectedIndex].value;
	if(dir != '/') 	{		
		changeLoadingStatus('load');
		window.open("ImageManager/images.php?act=updatedir&dirPath="+pard.value+"&subfolder="+def_subfolder,"imgManager","");		
	}	
}
function updateDir(newPath) {
	var dp=document.getElementById('dirPath');
	var allPaths =dp.options;
	//alert("new:"+newPath);
	for(i=0; i<allPaths.length; i++) 
	{
		//alert(allPaths.item(i).value);
		allPaths.item(i).selected = false;
		if((allPaths.item(i).value)==newPath) 
		{
			allPaths.item(i).selected = true;
		}
	}
	if (document.getElementById("url").value=='') layerVis("goThumb", 'hidden');
}
function doModel() {
	var dms=document.getElementById('dms');
	var val=dms.options[dms.selectedIndex].value.split('-');
	document.getElementById('thumbFolder').value=val[1];
	document.getElementById('thumbWidth').value=val[2];
	document.getElementById('thumbHeight').value=val[3];
	showImage();
	//document.getElementById('thumbQuality').value=val[3];
	//document.getElementById('resizeOpt').selectedIndex=val[4];
	//actionx(val[5],val[5]);
	
}