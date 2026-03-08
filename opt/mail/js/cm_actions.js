
var IdsVarMl="'ids='+getSelectedIds(tbl_mail,currentItem)";



CM_opt_mail_list=['mail',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_mail.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_mail.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_mail')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarMl],
			['sep'],
			['get-1','Pogledaj popis poslanih mailova','sentlist'],
			['get-1','Pogledaj popis neuspješnih slanja','sentlist',"'failed=1'"],
			['sep'],
			['test','isEdit()','get','Dodaj novi mail predložak','edit',"'mode=new'"]
			
		],"tbl_mail"
	];
CM_opt_mail_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_mail'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_mail&widths='+getTableWidths(tbl_mail)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_mail'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_mail')"]
		],"tbl_mail"
	 ]



	


