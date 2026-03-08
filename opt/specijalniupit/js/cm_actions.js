
var IdsVarSQL="'ids='+getSelectedIds(tbl_specijalniupit,currentItem)";



CM_opt_specijalniupit_list=['specijalniupit',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_specijalniupit.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_specijalniupit.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_specijalniupit')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test-1','isEdit()','get','Testiraj upit','go'],
			['test','isEdit() && (currentVar1<2)','del','Briši','delete','Jeste li sigurni?',IdsVarSQL],
			['sep'],
			['test','isEdit()','get','Dodaj novi specijalni upit','edit',"'mode=new'"]
			
		],"tbl_specijalniupit"
	];
CM_opt_specijalniupit_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_specijalniupit'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_specijalniupit&widths='+getTableWidths(tbl_specijalniupit)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_specijalniupit'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_specijalniupit')"]
		],"tbl_specijalniupit"
	 ]



	


