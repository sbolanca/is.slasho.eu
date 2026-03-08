
var IdsVarRa="'ids='+getSelectedIds(tbl_ponuda,currentItem)";




CM_opt_ponuda_list=['ponuda',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_ponuda.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_ponuda.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_ponuda')"]
			]],
			['sep'],
			['test-1','isEdit()','get','Pregled ponude','edit'],
			['sep'],
			//['getp','Print',"print-ponuda",IdsVarRa],
			['chkp','Print',"print-ponuda","Odaberite detalje koje želite uključiti u ispisu ponude:",["pdv|iznos s PDV-om|"+UPDV,"popust|polje popust|1"],IdsVarRa],
				
			['sep'],
			['test','isEdit()','getp','Poništi status','status',"'status=0&'+"+IdsVarRa],
			['test','isEdit()','getp','Status KREIRANO','status',"'status=1&'+"+IdsVarRa],
			['test','isEdit()','getp','Status OBRAĐENO','status',"'status=2&'+"+IdsVarRa],
			['test','isEdit()','getp','Status ARHIVIRANO','status',"'status=3&'+"+IdsVarRa],
			['test','isEdit()','getp','Status PROBLEM','status',"'status=4&'+"+IdsVarRa],
			['sep'],
			['test-1','isEdit()','get','Izradi račun na osnovu ove ponude','makeracun'],
			['sep'],
			['test-1','isEdit()','get','Dupliciraj ponudu','duplicate'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarRa],
			['sep'],
			['test','isEdit()','get','Dodaj novu ponudu','edit',"'mode=new'"]
			
			
		],"tbl_ponuda"
	];
CM_opt_ponuda_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_ponuda'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_ponuda&widths='+getTableWidths(tbl_ponuda)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_ponuda'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_ponuda')"]
		],"tbl_ponuda"
	 ]


CM_opt_ponuda_stv=['ponuda',
		[
				['get','Ukloni stavku','stv-remove',"'ponudaID='+currentVar1"],
				['del','Ukloni sve stavke','stv-removeall','Jeste li sigurni da želite ukloniti sve stavke?',"'ponudaID='+currentVar1"],
				['sep'],
				['test','isEdit()','ord','Spremi redoslijed','stv-order','stvlist']
		]
	 ];
function sMat(ix,f) { 
	aCMCP(ix,'ponuda','stv-save',f+'='+$('#sm_'+ix+' .'+f).val());
}


