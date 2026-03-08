	
CM_folder=['folder',
		[
			['exe','Otvori u glavnom prozoru',"location.href='index.php?opt=arhiva&act=folder&folderID='+currentItem"],
			['get','Pošalji mail Sonji','arhiva|itm-sendmail'],
			['sep'],
			['test','(myID==currentVar2)','get','Mjenjaj','fld-edit'],
			['test','!(myID==currentVar2)','get','Kopiraj meni ovakvu mapu','fld-copy'],
			['test','(myID==currentVar2)','del','Briši','fld-delete','Jeste li sigurni da želite izbrisati ovu mapu ?\nIzbrisat će se i sav njen sadržaj.'],
			['test','(myID==currentVar2)','del','Isprazni mapu','fld-empty','Jeste li sigurni da želite isprazniti ovu mapu ?'],
			['sep'],
			['test','(myID==currentVar2)','get','Dodaj podmapu','fld-newsub'],
			['test','(myID==currentVar2)','get','Dodaj mapu','fld-new',"'parentID='+currentVar1"],
			['sep'],
			['test','(myID==currentVar2)','sel','Vidljivost mape','fld-sharing','Ovaj folder mogu gledati:',["0|nitko osim mene|!currentVar3","4|određene osobe ... (odabir osoba)|currentVar3==4","5|SVI|currentVar3==5"]],
			['sep'],
			['exe','Refrešaj', "refreshFolders()"]
		]
	];
CM_folder0=['folder',
		[	['get','Kreiraj novu mapu','fld-new'],
			 ['exe','Refrešaj', "refreshMaps()"]
		]
	];

var IdsVarFld="'ids='+getSelectedIds(tblF,currentItem)";
CM_folder_I=['folder',
		[
			['del','Ukloni odabrano iz mape','itm-delete','Jeste li sigurni da želite ukloniti odabrano iz mape ?',"'folderID='+lastOpenMap+'&'+"+IdsVarFld],
			['sep'],
			['exe','Otvori mapu u glavnom prozoru',"location.href='index.php?opt=arhiva&act=folder&folderID='+lastOpenMap"]
			
		]
	];
function mapItem_cm(i,c,e) {doOnTblCM(i,c,e,tblF,'CM_folder_I'); }	

function map_cm(i,e) {doOnTreeCM(i,e,Ftree,'CM_folder');refreshMap(i);}	

			
function refreshMap(i) {
		if (!(lastOpenMap==i)) {
			var cmh=Ftree.getUserData(Ftree.getSelectedItemId(),'cm').split(',')[3];
			tblF.clearAll();
			tblF.enableSmartRendering(true,cmh);
			tblF.loadXML("ajx.php?opt=folder&act=list&folderID="+i);
		}
		lastOpenMap=i;
		firstMapLoading=0;
}
function refreshMaps() {
	tblF.clearAll();
	Ftree.deleteChildItems(0);
	Ftree.loadXML("ajx.php?opt=folder&act=folder&parentID=0");
	lastOpenMap=0;
}

function MapName() {
		return Ftree.getItemText(lastOpenMap);
}
firstMapLoading=1;
function openMap() {
		if (firstMapLoading && lastOpenMap) {
			var x=lastOpenMap;lastOpenMap=0;
			Ftree.openItem(x);
			Ftree.selectItem(x,0,0);
			refreshMap(x);
		}
	}
