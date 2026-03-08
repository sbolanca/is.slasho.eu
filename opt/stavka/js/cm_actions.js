
var IdsVarS="'ids='+getSelectedIds(tbl_stavka,currentItem)";



CM_opt_stavka_list=['stavka',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_stavka.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_stavka.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_stavka')"]
			]],
			['test',"pagetype=='folder'",'sub','Marker', [
				['getp','1|Plavo',"fld-marker","'value=1&folderID='+folderID+'&'+"+IdsVarS],	
				['getp','2|Zeleno',"fld-marker","'value=2&folderID='+folderID+'&'+"+IdsVarS],	
				['getp','3|Crveno',"fld-marker","'value=3&folderID='+folderID+'&'+"+IdsVarS],	
				['getp','4|Smeđe',"fld-marker","'value=4&folderID='+folderID+'&'+"+IdsVarS],	
				['getp','5|Ljubičasto',"fld-marker","'value=5&folderID='+folderID+'&'+"+IdsVarS],	
				['sep'],
				['getp','0|Poništi marker',"fld-marker","'value=0&folderID='+folderID+'&'+"+IdsVarS],
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['sep'],
			['getp','Postavi odabrane stavke u spremnik',"clp-set",IdsVarS],
			['test','clipboard.length','getp','Nadopuni spremnik odabranim stavkama',"clp-add",IdsVarS],
			['sep'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarS],
			['sep'],
			['test',"pagetype=='folder'",'getp','Ukloni iz foldera',"fld-remove","'folderID='+folderID+'&'+"+IdsVarS],
			['test','lastOpenFolder>0','delp',"exe:'Dodaj u folder \"'+FolderName()+'\"'",'fld-insert',"exe:'Jeste li sigurni da želite staviti odabrano u folder \"'+FolderName()+'\" ?'","'folderID='+lastOpenFolder+'&'+"+IdsVarS],
			['getp-N','Dodaj u novi folder',"fld-makefolder","'parentID='+Ftree.getParentId(lastOpenFolder)+'&'+"+IdsVarS],
			['test','lastOpenFolder>0','del',"exe:'Dodaj cijeli ispis u folder \"'+FolderName()+'\"'",'fld-results2folder',"exe:'Jeste li sigurni da želite staviti cijeli ispis rezultata u folder \"'+FolderName()+'\" ?!!'","'folderID='+lastOpenFolder"],
			['sep'],
			['sub','Postavi ...',[
				['getp','Postavi kao materijalna stavka',"materijal","'val=1&'+"+IdsVarS],
				['getp','Postavi kao usluga',"materijal","'val=0&'+"+IdsVarS],
				['sep'],
				['getp','Stopu PDV-a',"setpdv",IdsVarS]
				
			]],
			['sep'],
			['test','isEdit()','get','Dodaj novu stavku','edit',"'mode=new'"]
			
		],"tbl_stavka"
	];
CM_opt_stavka_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_stavka'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_stavka&widths='+getTableWidths(tbl_stavka)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_stavka'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_stavka')"]
		],"tbl_stavka"
	 ];
	 
CM_opt_stavka_clipboard=['stavka',
		[
				['test','clipboard.length && (pageopt=="stavka")','exe','Označi stavke iz spremnika','markClipboard()'],
				['sep'],
				['test','clipboard.length','get','Izlistaj stavke iz spremnika','clp-show'],
				['sep'],
				['test','clipboard.length','get','Isprazni','clp-empty'],
				['sep'],
				['exe','Export stavki iz spremnika','window.location.assign("rplc2.php?scr=opt/stavka/download/export");']

		]
	 ];
function markClipboard() {
	tbl_stavka.clearSelection();
	for(var i=0;i<clipboard.length;i++) tbl_stavka.selectRowById(clipboard[i],1);
}
CM_opt_stavka_cli=['stavka',
		[
				['get','Ukloni iz spremnika','clp-remove'],
				['sep'],
				['test','isEdit()','get','Pregled','edit']
		]
	 ];
	 
CM_opt_stavka_folder=['stavka',
		[
			['exe','Otvori u glavnom prozoru',"location.href='index.php?opt=stavka&act=folder&folderID='+currentItem"],
			['test','isView("album") && isSuper','del','Napravi novi album od stavki iz ovog foldera','fld-makealbum',"Jeste li sigurni da želite napraviti novi album od stavki iz ovog foldera?","'folderID='+currentItem+((opt=='album') ? '&refresh=1' : '')"],
			['sep'],
			['test','(myID==currentVar2)','get','Mjenjaj','fld-edit'],
			['test','!(myID==currentVar2)','get','Kopiraj meni ovakav folder','fld-copy'],
			['test','!(myID==currentVar2)','del','Ukloni da ga ne gledam','fld-hide','Kad ga uklonite, samo vam vlasnik foldera može omogućiti da ga ponovo vidite.'],
			['test','(myID==currentVar2)','del','Briši','fld-delete','Jeste li sigurni da želite izbrisati ovaj folder ?'],
			['sep'],
			['test','(myID==currentVar2)','get','Dodaj podfolder','fld-newsub'],
			['test','(myID==currentVar2)','get','Dodaj folder','fld-new',"'parentID='+currentVar1"],
			['sep'],
			['test','(myID==currentVar2)','sel','Vidljivost foldera','fld-sharing','Ovaj folder mogu gledati:',["0|nitko osim mene|!currentVar3","4|određene osobe ... (odabir osoba)|currentVar3==4","5|SVI|currentVar3==5"]],
			['test','(myID==currentVar2)','del','Vlasnik foldera','fld-owner','Promjena vlasnika foldera može dovesti do situacije da više ne vidite ovaj folder ukoliko je vidljivost ograničena.\n\nUnaprijed se osigurajte od toga tako da dodjelite vidljivost određenim osobama među kojima ste i vi, ili svima.'],
			['sep'],
			['exe','Refrešaj', "refreshFolders()"]
		]
	];
CM_opt_stavka_folder0=['stavka',
		[	['get','Kreiraj novi folder','fld-new'],
			 ['exe','Refrešaj', "refreshFolders()"]
		]
	];

var IdsVarFld="'ids='+getSelectedIds(tblF,currentItem)";
CM_opt_stavka_folder_I=['stavka',
		[
			['del','Ukloni odabrano iz foldera','fld-remove','Jeste li sigurni da želite ukloniti odabrano iz foldera ?',"'folderID='+lastOpenFolder+'&'+"+IdsVarFld],
			['sep'],
			['test','isView("album")','del','Napravi novi album od stavki iz ovog foldera','fld-makealbum',"Jeste li sigurni da želite napraviti novi album od stavki iz ovog foldera?","'folderID='+lastOpenFolder"],
			['del','Ukloni sve izbrisane stavke','fld-removedeleted',"Jeste li sigurni da želite ukloniti izbrisane stavke iz foldera?","'folderID='+lastOpenFolder"],
			['exe','Otvori folder u glavnom prozoru',"location.href='index.php?opt=stavka&act=folder&folderID='+lastOpenFolder"]
			
		],"tblF"
	];

	


