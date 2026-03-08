galleryID=0;

if (galleryID) {
	var gallCMItem=['del','Ukloni galeriju','removegallery',"Jeste li sigurni da želite ukloniti galeriju od ovog članka?"];
} else {
	var gallCMItem=['get','Kreiraj novu galeriju','newgallery'];
}

var showOrdering=false;

if (showOrdering) {
		var ordCMItem = ['ord','Spremi redosljed','ordersubitems','contentlist'];
} else {
		var ordCMItem = ['exe','Dozvoli promjenu redosljeda','cM_opt_news_allowordering();'];
}

function cM_opt_news_allowordering() {
	Sortable.create('contentlist',{tag:'div'});
	showOrdering=true;
	CM_opt_news_intro[2][8] = ['ord','Spremi redosljed','order','contentlist'];
}
	CM_opt_news_intro=['news',
		[
			['get','Mjenjaj tekst','editintro2',"'tf='+currentVar2"],			 
			['get','Objavljivanje','editdate',"'tf='+currentVar2"],			 
			['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj članak ?'],
			['get','Arhiviraj','archive'],
			['img','Slika','cM_opt_newsImageSave(fname)',152,'','','','currentItem'],
			['sep'],
			['get','Dodaj novo','addnew',"'tf='+currentVar2+'&gid='+groupID+'&pid='+parentID",'var parentID=currentVar1'],
			['sep'],
			ordCMItem
		]
	];
	CM_opt_news=['news',
		[
			['get','Mjenjaj tekst','edit'], 
			['get','Metatagovi','editmeta'],
			['get','Objavljivanje','editdate'],
			['get','Tip stranice','type'],
			['get','Intro','editintro'],
			['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj članak ?'],
			['sep'],
			['get','Postavi galeriju','changegallery'],			
			gallCMItem			 
		]
	];
	CM_opt_news2=['news',
		[
			['get','Mjenjaj tekst','edit'], 		 
			['get','Metatagovi','editmeta'],
			['get','Objavljivanje','editdate'],
			['get','Tip stranice','type'],
			['get','Intro','editintro'],	 
			['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj članak ?'],
			['get','Dodaj novo','addnew',"'gid='+groupID+'&pid='+parentID",'var parentID=currentVar1']
		]
	];
	CM_opt_news3=['news',
		[
		    ['get','Mjenjaj tekst','edit'],
			['get','Metatagovi','editmeta'],
			['get','Objavljivanje','editdate'],
			['get','Tip stranice','type'],
			['get','Intro','editintro'],
			['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj članak ?'],
			['get','Kopiranje jezika','copylang'],
			['sep'],
			['get','Postavi galeriju','changegallery'],
			['get','Dodaj novo','addnew',"'gid='+groupID+'&pid='+parentID",'var parentID=currentVar1'],
			gallCMItem			
		]
	];
	

	CM_opt_newsgroup=['news',
		[
			['get','Mjenjaj tekst','editgroup'],
			['get','Tip stranice','type',"'class=Group'"],
			['get','Dodaj novo','addnew',"'gid='+groupID+'&pid='+parentID",'var parentID=currentVar1']
		]
	];
	CM_opt_newssub=['news',
		[	['get','Dodaj novo','addnew',"'gid='+groupID+'&pid='+parentID",'var parentID=currentVar1'] ] ];
	CM_opt_newssubitem=['news',
		[	['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj članak ?'] ] ];
	
	
	
	var CM_opt_news_photo=['news',
	 [
		['get','Mjenjaj opis slike','editphotoitem'],
		['img','Mjenjaj sliku','cM_opt_news_photoItemSave(fname)',152],			 
		['del','Briši','deletephotoitem','Jeste li sigurni da želite izbrisati ovu sliku?'],			 
		['sep'],
		['get','Mjenjaj opis galerije','editgallery','','currentItem=galleryID;'],
		['img','Dodaj novu sliku','cM_opt_news_photoItemNewSave(fname)',152],		
		['ord','Spremi redosljed slika','orderphotoitems','gallery'],
		['sep'],
		['del','Potpuno izbriši galeriju','deletegallery','Jeste li sigurni da želite izbrisati ovu galeriju?',"'cid='+cid","currentItem=galleryID;"]
	  ]
	];

	var CM_opt_news_photo_simple=['news',
	 [
		['get','Mjenjaj opis galerije','editgallery'],
		['img','Dodaj novu sliku','cM_opt_news_photoItemNewSave(fname)',152]			
	 ]
	];
	
	CM_opt_news_act=['news',
			[['del','Ukloni','remove',"Jeste li sigurni da želite ukloniti ovaj članak sa liste?",'"fld=listing"']]];

function cM_opt_newsImageSave(fname) {	
	activateCMCommand('news','changeimage','tf='+currentVar2+'&file='+fname);
}


function cM_opt_news_photoItemSave(fname) {	
	activateCMCommand('news','changephotoimage','file='+fname+'&gid='+galleryID);
}

function cM_opt_news_photoItemNewSave(fname) {
	newfrm = document.getElementById('_temppinsform');
	if (!newfrm) newfrm=generateHiddenForm('_temppinsform','id,galleryID,image,published');
	setGeneratedField('_temppinsform','id',0);
	setGeneratedField('_temppinsform','image',fname);
	setGeneratedField('_temppinsform','galleryID',galleryID);
	setGeneratedField('_temppinsform','published',1);
	activateCMCommandPOST('news','addnewphotoitem','_temppinsform','cid='+cid);
}
