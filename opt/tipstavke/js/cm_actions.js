
var IdsVarTS="'ids='+getSelectedIds(tbl_tipstavke,currentItem)";



CM_opt_tipstavke_list=['tipstavke',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_tipstavke.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_tipstavke.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_tipstavke')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarTS],
			['sep'],
			['test','isEdit()','get','Dodaj novog tipstavkea','edit',"'mode=new'"]
			
		],"tbl_tipstavke"
	];
CM_opt_tipstavke_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_tipstavke'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_tipstavke&widths='+getTableWidths(tbl_tipstavke)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_tipstavke'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_tipstavke')"]
		],"tbl_tipstavke"
	 ]



	


