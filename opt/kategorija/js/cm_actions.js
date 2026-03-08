
var IdsVarK="'ids='+getSelectedIds(tbl_kategorija,currentItem)";



CM_opt_kategorija_list=['kategorija',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_kategorija.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_kategorija.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorija')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarK],
			['sep'],
			['test','isEdit()','get','Dodaj novu kategoriju','edit',"'mode=new'"]
			
		],"tbl_kategorija"
	];
CM_opt_kategorija_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_kategorija'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_kategorija&widths='+getTableWidths(tbl_kategorija)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_kategorija'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorija')"]
		],"tbl_kategorija"
	 ]



	


