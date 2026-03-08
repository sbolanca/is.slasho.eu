var IdsVarL="'ids='+getSelectedIds(tbl_log,currentItem)";
CM_opt_log=['log',
		[
			//['get','Pregled','view0'],
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_log.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_log.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_log')"]
			]],
			['get','Detalji','view'],
			['test','(myID<2)','getp','Briši','delete',IdsVarL]
			
		],"tbl_log"
	];
CM_opt_log_view=['log',
		[
			['get','Detalji','view']
			
		]
	];
CM_opt_log_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_log'"],
				['getp','Spremi postavke tablice','tblwidths',"'table=tbl_log&widths='+getTableWidths(tbl_log)"],
				['sep'],
				['del','Namjesti defaultne postavke tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne postavke tablice?',"'table=tbl_log'"]
		],"tbl_log"
	 ]


function logOA(oa) {
	document.getElementById('oa').value=oa;
	hideStandardPopup('editbox');
}