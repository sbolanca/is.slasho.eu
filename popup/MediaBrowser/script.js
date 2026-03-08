
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
	if (a=='upload') {
		
		
		
		var f=xtractFile(forma.upload.value);
		//var s=false;
		var s=true;
		//for (var i=0;i<allowedExt.length;i++){
		//		if (allowedExt[i]==f.ext) s=true;
		//}
		if (s)  forma.submit(); 
		else alert('Nedozvoljeni tip datoteke!');
	}
	else submitforma2();	
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
		//alert(elem2.name+": "+val);
	}
	forma2.submit();
}

function Init() {
    var elmSelectedImage;
    var htmlSelectionControl = "Control";

    document.body.onkeypress = _CloseOnEsc;

    document.getElementById("url").fImageLoaded = false;
	   
  //  document.getElementById("f_url").value = opener.adminForm.file_location.value;  // fix placeholder src values that editor converted to abs paths
                

    document.getElementById("url").value = (document.getElementById("url").value) || "http://";

}



function onOK() {
	
    var elmImage;
    var elmImageX;
    var intAlignment;
    var htmlSelectionControl = "Control";
   // var opener = window.dialogArguments;

    // error checking
    var required = {
    "url": "Please enter a URL."
  };
  for (var i in required) {
    var el = document.getElementById(i);
    if (!el.value) {
      alert(required[i]);
      el.focus();
      return false;
    }
  }

	opener.doitf(document.getElementById("url").value,imbox,imvalue);
	
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
	/*
	var st='';
	eval ("st=lconst.ls_"+state+";");
	setObjectProp('loadingStatus','innerHTML',st);
	layerVis('loading','visible')
	*/
}

function goUpDir() {
	var pard=document.getElementById('parentDir');
	var dp=document.getElementById('dirPath');
	var dir = dp.options[dp.selectedIndex].value;
	if(dir != '/') 	{		
		changeLoadingStatus('load');
		window.open("MediaBrowser/images.php?act=updatedir&dirPath="+pard.value+"&subfolder="+def_subfolder,"imgManager","");		
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
	//layerVis("goThumb", 'hidden');
}
function xtractFile(data){
            data = data.replace(/^\s|\s$/g, "");

            if (/\.\w+$/.test(data)) {
                var m = data.match(/([^\/\\]+)\.(\w+)$/);
                if (m)
                    return {filename: m[1], ext: m[2]};
                else
                    return {filename: "no file name", ext:null};
            } else {
                var m = data.match(/([^\/\\]+)$/);
                if (m)
                    return {filename: m[1], ext: null};
                else
                    return {filename: "no file name", ext:null};
            }
        }
