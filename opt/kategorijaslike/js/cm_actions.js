
var IdsVarKSL="'ids='+getSelectedIds(tbl_kategorijaslike,currentItem)";



CM_opt_kategorijaslike_list=['kategorijaslike',
		[	
			
			['sub','Info o rezultatima', [
				['exe-N','Pobroji odabrane',"alert(tbl_kategorijaslike.selectedRows.length)"],
				['exe','Redni broj zapisa u tablici',"alert(tbl_kategorijaslike.getRowIndex(currentItem)+1)"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorijaslike')"]
			]],
			['test-1','isEdit()','get','Mijenjaj','edit'],
			['test','isEdit()','del','Briši','delete','Jeste li sigurni?',IdsVarKSL],
			['sep'],
			['test','isEdit()','get','Dodaj novu kategoriju slike','edit',"'mode=new'"],
			['sep'],
			['test','isEdit() && !dragging_KSTbl_enabled','exe','Omogući izmjenu redoslijeda',"dragTblKSToggle(true)"],			
			['test','isEdit() && dragging_KSTbl_enabled','getp','Spremi redoslijed','order',"'ord='+tbl_kategorijaslike.getAllItemIds('|')"]			

			
		],"tbl_kategorijaslike"
	];
CM_opt_kategorijaslike_TBL=['settings',
		[
				['get','Prikaz stupaca','tblcols',"'table=tbl_kategorijaslike'"],
				['getp','Spremi poslike tablice','tblwidths',"'table=tbl_kategorijaslike&widths='+getTableWidths(tbl_kategorijaslike)"],
				['sep'],
				['del','Namjesti defaultne poslike tablice','tbldefault','Jeste li sigurni da želite vratiti defaultne poslike tablice?',"'table=tbl_kategorijaslike'"],
				['sep'],
				['exe','Pronađi red u tablici',"gotoTableRow('tbl_kategorijaslike')"]
		],"tbl_kategorijaslike"
	 ]

dragging_KSTbl_enabled=false;	
function dragTblKSToggle(go) {
	if(go) {
		tbl_kategorijaslike.enableDragAndDrop(true);
		activateCMCommandPOST('kategorijaslike',pageact,'searchKSForm');
		dragging_KSTbl_enabled=true;	
	} else {
		tbl_kategorijaslike.enableDragAndDrop(false);
		activateCMCommandPOST('kategorijaslike',pageact,'searchKSForm');
		dragging_KSTbl_enabled=false;	
	}
}

	


