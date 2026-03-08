var IdsVarU="'ids='+getSelectedIds(tbl_user,currentItem)";



if (allowEdit) CM_opt_user=['user',
		[
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_user.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_user.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_user')"]
			]],
			
			
			['sep'],
			['get','Pošalji mail ...','mail|mail',IdsVarU],
			['sep'],
		
			['get-1','Mjenjaj lozinku','password'],		 
			['get-1','Mjenjaj podatke','edit'],
			['get-1','Dozvole','dozvole'],
			['sep'],
			
			['get','Dodaj novog djelatnika','new'],
			['sep'],
			['getp','Aktivacija','activate',IdsVarU],
			['delp','Briši','delete','Jeste li sigurni da želite izbrisati ovog korisnika ?',IdsVarU],
			['sep'],
			['exe-1','Pregledaj log',"location.href='index.php?opt=log&log_userID='+currentItem"]
			
			
		],'tbl_user'
	];
else CM_opt_user=['user',
		[
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_user.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_user.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_user')"]
			]]			
		],'tbl_user'
	];
	

CM_opt_user_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_user'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_user&widths='+getTableWidths(tbl_user)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_user'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_user')"]
		],"tbl_user"
	 ]

			