
var IdsVarRa="'ids='+getSelectedIds(tbl_racun,currentItem)";




CM_opt_racun_list=['racun',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_racun.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_racun.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_racun')"]
			]],
			['sep'],
			['test-1','isEdit()','get','Pregled računa','edit'],
			['sep'],
			['selp','Print',"print-racun","Printanje računa",["1|sa potpisom|1","0|bez potpisa"],IdsVarRa],
				
			['sep'],
			['test','isEdit()','getp','Poništi status','status',"'status=0&'+"+IdsVarRa],
			['test','isEdit()','getp','Status KREIRANO','status',"'status=1&'+"+IdsVarRa],
			['test','isEdit()','getp','Status OBRAĐENO','status',"'status=2&'+"+IdsVarRa],
			['test','isEdit()','getp','Status ARHIVIRANO','status',"'status=3&'+"+IdsVarRa],
			['test','isEdit()','getp','Status PROBLEM','status',"'status=4&'+"+IdsVarRa],
			['sep'],
			['test-1','isEdit()','get','Dupliciraj račun','duplicate'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarRa],
			['sep'],
			['test','isEdit()','get','Dodaj novi račun','edit',"'mode=new'"]
			
			
		],"tbl_racun"
	];
CM_opt_racun_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_racun'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_racun&widths='+getTableWidths(tbl_racun)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_racun'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_racun')"]
		],"tbl_racun"
	 ]


CM_opt_racun_stv=['racun',
		[
				['get','Ukloni stavku','stv-remove',"'racunID='+currentVar1"],
				['del','Ukloni sve stavke','stv-removeall','Jeste li sigurni da želite ukloniti sve stavke?',"'racunID='+currentVar1"],
				['sep'],
				['test','isEdit()','ord','Spremi redoslijed','stv-order','stvlist']
		]
	 ];
function sMat(ix,f) { 
	aCMCP(ix,'racun','stv-save',f+'='+$('#sm_'+ix+' .'+f).val());
}

var IdsVarRNa="'ids='+getSelectedIds(tbl_racunnapomena,currentItem)";

CM_opt_racunnapomena_list=['racun',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_racunnapomena.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_racunnapomena.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_racunnapomena')"]
			]],
			['sep'],
			['test-1','isEdit()','get','Mijenjaj','nap-edit'],
			['test','isEdit()','del','Briši','nap-delete','Jeste li sigurni?',IdsVarRNa],
			['sep'],
			['test','isEdit()','get','Dodaj novu napomenu račun','nap-edit',"'mode=new'"]
			
			
		],"tbl_racun"
	];
CM_opt_racunnapomena_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_racunnapomena'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_racunnapomena&widths='+getTableWidths(tbl_racunnapomena)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_racunnapomena'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_racunnapomena')"]
		],"tbl_racun"
	 ]
