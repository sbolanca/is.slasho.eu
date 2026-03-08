<? 
session_start();
$basePath = dirname( __FILE__ );
$path = str_replace("edit/FCKeditor/editor/plugins/VannaContent","",$basePath);
$path = str_replace("edit\FCKeditor\editor\plugins\VannaContent","",$path);
require( $path."configuration.php" );
$jdb0=intval($_SESSION["jah_debug0"]);
$jdb1=intval($_SESSION["jah_debug1"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Vanna Link - Insert Vanna Made Simple Link</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta content="noindex, nofollow" name="robots">
		<script type="text/javascript" src="fckvannacontent.js"></script>
		<script type="text/javascript" src="<? echo $simConfig_live_site; ?>/js/jah.js"></script>
		<script type="text/javascript" src="<? echo $simConfig_live_site; ?>/js/admin_fck.js"></script>
		
		
		
		<script type="text/javascript">
		<!--
			var oEditor			= window.parent.InnerDialogLoaded(); 
			var FCK				= oEditor.FCK; 
			var FCKLang			= oEditor.FCKLang ;
			var FCKConfig		= oEditor.FCKConfig ;
			var FCKCMSContent	= oEditor.FCKCMSContent; 
			var simConfig_live_site='<? echo $simConfig_live_site; ?>';
			var jahActive=true;
			var lang='<? echo $_GET["lang"]; ?>';
			var jah_debug0=<? echo $jdb0; ?>;
			var jah_debug1=<? echo  $jdb1 ?>;
			var lang='<? echo $_GET["lang"]; ?>';
			var currentItem=0;
			// oLink: The actual selected link in the editor.
			var oLink = FCK.Selection.MoveToAncestorNode( 'A' ) ;
			if ( oLink )
				FCK.Selection.SelectNode( oLink ) ;
			
			
			
			window.onload = function ()	{ 
				// First of all, translates the dialog box texts.
				oEditor.FCKLanguageManager.TranslatePage(document);
				
				LoadSelected();							//See function below 
				window.parent.SetOkButton( true );		//Show the "Ok" button. 
				
			} 
			 
			//If an anchor (A) object is currently selected, load the properties into the dialog 
			function LoadSelected()	{
				var sSelected;
				
				if ( oEditor.FCKBrowserInfo.IsGecko ) {
					sSelected = FCK.EditorWindow.getSelection();
				} else {
					sSelected = FCK.EditorDocument.selection.createRange().text;
				}
				if ( sSelected == "" ) {
					alert( 'Please select a text in order to create a (internal) link' );
				}
				document.hiddenForm.title.value=sSelected;
				if ( oLink ) {
					var sHRef = oLink.getAttribute( '_fcksavedurl' ) ;
					if ( !sHRef || sHRef.length == 0 )
						sHRef = oLink.getAttribute( 'href' , 2 ) + '' ;
					if (sHRef.substr(0,10)=='{vnn_link ') {
						var param_str=sHRef.substr(10,sHRef.length-11);
						var param_arr=param_str.split('|');
						var param='type='+param_arr[0]+'&'+ 
								  'mopt='+param_arr[1]+'&'+ 
								  'mact='+param_arr[2]+'&'+ 
								  'dbindex='+param_arr[4]+'&'+ 
								  'gid='+param_arr[5];
								
					} 
				}
				activateCMCommandPOST('menu','link','hiddenForm',param);
			}
			
			
			
			function Ok() {
				activateCMCommandPOST('menu','savelink','menuEditForm');
				return false;
				
			} 
			function DoitAndClose(type,mopt,mact,target,lnk,index,gid) {
				var plnk=lnk.replace('&amp;','&' )
				var
				oLink = oEditor.FCK.CreateLink('{vnn_link '+type+'|'+mopt+'|'+mact+'|'+plnk+'|'+index+'|'+gid+'}') ;
				
				window.parent.close();
			}
			
		//-->
		</script>
	</head>
			
	<body scroll="no" >
	<form id="hiddenForm" name="hiddenForm" method="POST">
	<input type="hidden" name="title" value=""/>
	</form>
		
	<div id="editbox" class="hiddendiv">
	<div class="popuptrack" id="editdrag"><div id="popuptitle" ></div>
		<div class="popupclosebtn" onClick="hideStandardPopup('editbox');"></div>
		<div class="cleaner"></div>
	</div>
	<div id="ed_content" class="areabox"></div>
</div>	
	</body>
</html> 
