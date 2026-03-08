
var IdsVarKL="'ids='+getSelectedIds(tbl_klijent,currentItem)";



CM_opt_klijent_list=['klijent',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_klijent.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_klijent.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_klijent')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarKL],
			['sep'],
			['test-1','isEdit()','get','Napravi novi račun','racun|edit',"'mode=new&klijentID='+currentItem"],
			['test-1','isEdit()','get','Napravi novu ponudu','ponuda|edit',"'mode=new&klijentID='+currentItem"],
			['sep'],
			['test','isEdit()','get','Dodaj novog klijenta','edit',"'mode=new'"]
			
		],"tbl_klijent"
	];
CM_opt_klijent_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_klijent'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_klijent&widths='+getTableWidths(tbl_klijent)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_klijent'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_klijent')"]
		],"tbl_klijent"
	 ]



	


