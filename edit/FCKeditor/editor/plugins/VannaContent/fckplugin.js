/* 
 *  FCKPlugin.js
 *  ------------
 *  This is a generic file which is needed for plugins that are developed
 *  for FCKEditor. With the below statements that toolbar is created and
 *  several options are being activated.
 *
 *  See the online documentation for more information:
 *  http://wiki.fckeditor.net/
 */

// Register the related commands.
FCKCommands.RegisterCommand(
	'VannaContent',
	new FCKDialogCommand(
		'VannaContent',
		FCKLang["VannaContentDlgTitle"],
		FCKPlugins.Items['VannaContent'].Path + 'fck_vannacontent.php?lang='+FCKConfig.langID,
		400,
		600
	)
);


 
// Create the "CMSContent" toolbar button.
// FCKToolbarButton( commandName, label, tooltip, style, sourceView, contextSensitive )
var oVannaContentItem = new FCKToolbarButton( 'VannaContent', FCKLang["VannaContentBtn"], null, null, false, true ); 
oVannaContentItem.IconPath = FCKConfig.PluginsPath + 'VannaContent/vannacontent.gif'; 

// 'CMSContent' is the name that is used in the toolbar config.
FCKToolbarItems.RegisterItem( 'VannaContent', oVannaContentItem );
