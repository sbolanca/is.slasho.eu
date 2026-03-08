<?
		$mainFrame->isSuper= intval($_SESSION['MM_super']);
		$mainFrame->isAdmin= intval($_SESSION['MM_admin']);
		$mainFrame->adminUsername= $_SESSION['MM_Username'];
		$mainFrame->adminName= $_SESSION['MM_name'];
		$mainFrame->adminEmail= $_SESSION['MM_email'];
		$mainFrame->adminId= intval($_SESSION['MM_id']);
		$mainFrame->setMetaRobots("none");
		$mainFrame->addMetaName("Generator","MirnaFrame - Copyright 2014 POSLuH d.o.o. All rights reserved.");
		$mainFrame->addCSS(auto_version("style.css"),"maincss");
		$mainFrame->addCSS("css/admin.css","admincss");
		$mainFrame->addCSS("css/color".$_SESSION['colorset'].".css","colorcss");
		$mainFrame->addCSS('jq/css/multiple-select.css',"multiplecss");//$mainFrame->includeScript("edit/FCKeditor/fckeditor.js","editor");
		$mainFrame->addCSS('jq/css/ui-custom/jquery-ui.css',"theme");
		$mainFrame->addCSS('css/ui-additional.css',"ui-additional");
		$mainFrame->includeScript("jq/jquery-1.8.3.min.js","jquery");
		$mainFrame->includeScript("jq/jquery-ui-1.10.2.custom.min.js","jquery-ui");
		$mainFrame->includeScript("jq/datepicker-hr.js","datepicker-hr");
		$mainFrame->includeScript("jq/jquery.stylish-select.js","jquery-stylish");
		$mainFrame->includeScript("jq/jquery.uniform.min.js","jquery-uniform");
		$mainFrame->includeScript('jq/jquery.multiple.select.js',"multiplejq");
		$mainFrame->includeScript("jq/jsound/jsound.js","jsound");		
		
		$mainFrame->includeScript(auto_version("js/jah.js"),"jah");		
		$mainFrame->includeScript(auto_version("js/common.js"),"common");		
		$mainFrame->includeScript("js/contextMenu.js","contextmenu");		
		
		$mainFrame->includeScript("js/codebase/dhtmlxcommon.js","dhtmlxcommon");		
		
		
		//$mainFrame->includeScript("js/dhtmlxTree/codebase/dhtmlxcommon.js","dhtmlxcommon");	
		$mainFrame->includeScript("js/codebase/dhtmlxgrid.js","dhtmlxgrid");		
		$mainFrame->includeScript("js/codebase/dhtmlxgridcell.js","dhtmlxgridcell");	
		$mainFrame->includeScript("js/codebase/ext/dhtmlxgrid_srnd.js","dhtmlxgrid_srnd");		
		$mainFrame->includeScript("js/codebase/ext/dhtmlxgrid_drag.js","dhtmlxgrid_drag");	
		$mainFrame->includeScript("js/dhtmlxTree/codebase/dhtmlxtree.js","dhtmlxtree");	
		
		$mainFrame->addCSS("js/dhtmlxTree/codebase/dhtmlxtree.css","dhtmlxtree");
		
		$mainFrame->addCSS("js/codebase/dhtmlxgrid.css","dhtmlxgridcss");		
		

		
		
		$mainFrame->includeScript(auto_version("js/admin.js"),"admin");		
		$mainFrame->includeScript("js/scriptloader.js","scriptloader");		
		//$mainFrame->addHeaderScript("var deviceType='".$mainFrame->deviceType."';","deviceType");
		//$mainFrame->addHeaderScript("var isMobile=".($mainFrame->deviceType=='mobile'?1:0).";","isMobile");
		//$mainFrame->addHeaderScript("var isTablet=".($mainFrame->deviceType=='tablet'?1:0).";","isTablet");
		//$mainFrame->addHeaderScript("var isComputer=".($mainFrame->deviceType=='computer'?1:0).";","isTablet");
		$mainFrame->addHeaderScript("var isAdmin=".$mainFrame->isAdmin.";","isAdmin");
		$mainFrame->addHeaderScript("var isSuper=".$mainFrame->isSuper.";","isSuper");
		$mainFrame->addHeaderScript("var lang='".$lang."';","setlang");
		$mainFrame->addHeaderScript("var alang='".$simConfig_alang."';","setalang");
		$mainFrame->addHeaderScript("var cid=".$id.";","setId");
		$mainFrame->addHeaderScript("var simConfig_YEAR=".$simConfig_YEAR.";","simConfig_YEAR");
		
		$mainFrame->addHeaderScript("var currentItem=0; var currentColIndex=0; var currentVar1=0; var currentVar2=0;","setCurrentVars");
		//$mainFrame->addHeaderScript("var oFCKeditor=null;","ofckinstance");
		$mainFrame->addHeaderScript("var simConfig_live_site='$simConfig_live_site';","simConfig_live_site");
		$mainFrame->jah_debug0= intval($_SESSION['jah_debug0']);
		$mainFrame->jah_debug1= intval($_SESSION['jah_debug1']);
		$mainFrame->jah_hidemsg= intval($_SESSION['jah_hidemsg']);
		$mainFrame->jah_published= intval($_SESSION['jah_published']);
		$mainFrame->addHeaderScript("var jah_debug0=".$_SESSION['jah_debug0'].";","jah_debug0");
		$mainFrame->addHeaderScript("var isSuper=".$mainFrame->isSuper.";","isSuper");
		$mainFrame->addHeaderScript("var jah_debug1=".$_SESSION['jah_debug1'].";","jah_debug1");
		$mainFrame->addHeaderScript("var myID=".$mainFrame->adminId.";","myID");
		$mainFrame->addHeaderScript("var pageact='".$act."';","pageact");
		$mainFrame->addHeaderScript("var pageopt='".$opt."';","pageopt");
		
		
?>