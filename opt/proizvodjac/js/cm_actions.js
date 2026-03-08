
var IdsVarP="'ids='+getSelectedIds(tbl_proizvodjac,currentItem)";



CM_opt_proizvodjac_list=['proizvodjac',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_proizvodjac.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_proizvodjac.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_proizvodjac')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarP],
			['sep'],
			['test','isEdit()','get','Dodaj novog proizvođača','edit',"'mode=new'"]
			
		],"tbl_proizvodjac"
	];
CM_opt_proizvodjac_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_proizvodjac'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_proizvodjac&widths='+getTableWidths(tbl_proizvodjac)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_proizvodjac'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_proizvodjac')"]
		],"tbl_proizvodjac"
	 ]



	


