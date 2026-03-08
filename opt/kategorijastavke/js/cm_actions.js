
var IdsVarKS="'ids='+getSelectedIds(tbl_kategorijastavke,currentItem)";



CM_opt_kategorijastavke_list=['kategorijastavke',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_kategorijastavke.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_kategorijastavke.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorijastavke')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarKS],
			['sep'],
			['test','isEdit()','get','Dodaj novu kategoriju stavke','edit',"'mode=new'"]
			
		],"tbl_kategorijastavke"
	];
CM_opt_kategorijastavke_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_kategorijastavke'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_kategorijastavke&widths='+getTableWidths(tbl_kategorijastavke)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_kategorijastavke'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorijastavke')"]
		],"tbl_kategorijastavke"
	 ]



	


