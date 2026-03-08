
if (galleryID>0) {
	var gallCMItem=['del','Ukloni galeriju','removegallery','Jeste li sigurni da želite ukloniti galeriju od ovog sadržaja?'];
} else {
	var gallCMItem=['get','Kreiraj novu galeriju','newgallery'];
}


	var CM_opt_content=	['content',
	 [
		['get','Mjenjaj sadržaj','edit'],			 
		['get','Metatagovi','editmeta'],
		['get','Tip stranice','type'],
		['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj sadržaj ?'],
		['get','Intro','editintro'],
			//new CM_Item('ceditp','Change parent',cM_opt_content_ChangeParent),
		['sep'],
		['get','Postavi galeriju','changegallery'],
		gallCMItem		
	 ]
	];

	var CM_opt_contentsub=['content',
		[['get','Dodaj podsadržaj','addsub']]
	];
	var CM_opt_contentsubitem=['content',
	 [
		['del','Briši','delete','Jeste li sigurni da želite izbrisati ovaj sadržaj ?'],
		['sep'],
		['ord','Snimi redosljed','ordersubitems','submenues']
	 ]
	];

	var CM_opt_content_photo=['content',
	 [
		['get','Mjenjaj opis slike','editphotoitem'],
		['img','Mjenjaj sliku','cM_opt_content_photoItemSave(fname)',136],			 
		['del','Briši','deletephotoitem','Jeste li sigurni da želite izbrisati ovu sliku?'],			 
		['sep'],
		['get','Mjenjaj opis galerije','editgallery','','currentItem=galleryID;'],
		['img','Dodaj novu sliku','cM_opt_content_photoItemNewSave(fname)',136],		
		['ord','Spremi redosjled slika','orderphotoitems','gallery'],
		['sep'],
		['del','Potpuno izbriši galeriju','deletegallery','Jeste li sigurni da želite izbrisati ovu galeriju?',"'cid='+cid","currentItem=galleryID;"]
	  ]
	];

	var CM_opt_content_photo_simple=['content',
	 [
		['get','Mjenjaj opis galerije','editgallery'],
		['img','Dodaj novu sliku','cM_opt_content_photoItemNewSave(fname)',136]			
	 ]
	];



function cM_opt_content_photoItemSave(fname) {	
	activateCMCommand('content','changephotoimage','file='+fname+'&gid='+galleryID+'&cid='+cid);
}

function cM_opt_content_photoItemNewSave(fname) {
	newfrm = document.getElementById('_temppinsform');
	if (!newfrm) newfrm=generateHiddenForm('_temppinsform','id,galleryID,image,published');
	setGeneratedField('_temppinsform','id',0);
	setGeneratedField('_temppinsform','image',fname);
	setGeneratedField('_temppinsform','galleryID',galleryID);
	setGeneratedField('_temppinsform','published',1);
	activateCMCommandPOST('content','addnewphotoitem','_temppinsform','cid='+cid);
}
