
	CM_opt_reg=['register',
		[['get','Pogledaj listu korisnika','list']] 						 
	];
	
CM_opt_reg_list=['register',
 [
 	['get','Aktivacija','active'],			 						 
 	['del','Briši','delete',"Jeste li sigurni da želite izbrisati ovog korisnika?"],			 						 
 	['sep'],
	['exe','Dodaj novog korisnika','location.href="index.php?opt=register&act=new&lang='+lang+'";'],	
 	['exe','Export Excel liste','location.href="opt/register/export_excel.php";']			 						 
 ]
];
CM_opt_reg_list2=['register',
  [
	['get','cm_view','show'],
	['get','Editiranje korisnika','edit'],
	['get','Aktivacija','active'],		 						 
	['del','Briši','delete',"Jeste li sigurni da želite izbrisati ovog korisnika?"],	
	['sep'],	
	['get','Dodaj novog korisnika','show'],		
	['exe','Export Excel liste','location.href="opt/register/export_excel.php";']	
  ]			
 ];
