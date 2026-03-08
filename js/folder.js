
	
	
	
	function fldItem_cm(i,c,e) {doOnTblCM(i,c,e,tblF,'CM_opt_'+optF+'_folder_I'); }	

	function fld_cm(i,e) {doOnTreeCM(i,e,Ftree,'CM_opt_'+optF+'_folder');refreshFolder(i);}	


function openFolder(x) {
	if (Ftree.getIndexById(x)==null) aCMC(x,optF,'fld-getpath');
	else focusFolder(x);
	
}
function focusFolder(x) {	
	Ftree.openItem(x);
	Ftree.selectItem(x,0,0);
}

function openFolderInterval() {
	
	if (!(Ftree.getIndexById(pthA[currCounter])==null)) {
		Ftree.openItem(pthA[currCounter]);
		currCounter++;
		
		if (currCounter==pthA.length) {
			clearInterval(intervalOpenFolderID);
		 	Ftree.selectItem(pthA[currCounter-1],0,0);
		}
	} 
}

function openPath(pth) {
	
	pthA=pth.split(',');
	currCounter=0;
	
	while (!(Ftree.getIndexById(pthA[currCounter])==null) && (currCounter<pthA.length)) {
			focusFolder(pthA[currCounter]);
			currCounter++;	
	}
	intervalOpenFolderID=setInterval(openFolderInterval,1);
	
}			
function refreshFolder(i) {
		if (!(lastOpenFolder==i)) {
			var cmh=Ftree.getUserData(Ftree.getSelectedItemId(),'cm').split(',')[3];
			loadTable(tblF,"opt="+optF+"&act=folderlist&folderID="+i,cmh,true,100);
		}
		lastOpenFolder=i;
		aCMC(i,optF,'fld-session');
}
function refreshFolders() {
	tblF.clearAll();
	Ftree.deleteChildItems(0);
	Ftree.loadXML("ajx.php?opt="+optF+"&act=folder&parentID=0");
	lastOpenFolder=0;
}

function FolderName() {
		return Ftree.getItemText(lastOpenFolder);
}

$(document).ready(function () {
	
	Ftree=new dhtmlXTreeObject("Fboxtree","280","100%",0);
			Ftree.setImagePath("js/dhtmlxTree/codebase/imgs/csh_myblue/");
			Ftree.autoScroll=true;
			//if (allowEdit) {
				//Ftree.enableDragAndDrop(1);
				//Ftree.attachEvent("onDrag"|treeOnDrag);
			//}
			Ftree.setOnClickHandler(refreshFolder);
			Ftree.setOnRightClickHandler(fld_cm);	
			Ftree.setXMLAutoLoading("ajx.php?opt="+optF+"&act=folder")
			Ftree.loadXML("ajx.php?opt="+optF+"&act=folder&parentID=0");
			
	tblF = new dhtmlXGridObject('Fgridbox');
	tblF.setImagePath("js/codebase/imgs/");
	setHeaderTable("tblF_"+optF,"tblF");
	tblF.setInitWidths(setTableCols("tblF_"+optF,"W"));
	tblF.setColAlign(setTableCols("tblF_"+optF,"A"));
	tblF.setColTypes(setTableCols("tblF_"+optF,"T"));

	tblF.enableMultiselect(true);
	tblF.enableCellIds(true);
	tblF.attachEvent("onRightClick",fldItem_cm);
		
	tblF.attachEvent("onRowSelect",stdRowSelected);
		

	
	tblF.enablePreRendering(30);
	tblF.enableSmartRendering(true,100);
	tblF.init();
	
	
	
	var intervalOpenFolderID=null;
	var	pthA=null;
	var currCounter=0;
	lastOpenFolder=startLastOpenFolder;
	if (lastOpenFolder) {openFolder(lastOpenFolder)}
	/*
	$(window).focus(function() {
		aCMC(0,'snimka','clp-refreshalbum');
	});
	*/
})