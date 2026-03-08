
var VannaScriptLoader = new Object() ;
VannaScriptLoader.IsLoading = false ;
VannaScriptLoader.Queue = new Array() ;

// Adds a script or css to the queue.
VannaScriptLoader.AddScript = function( scriptPath )
{
	VannaScriptLoader.Queue[ VannaScriptLoader.Queue.length ] = scriptPath ;
	
	if ( !this.IsLoading )
		this.CheckQueue() ;
}

// Checks the queue to see if there is something to load.
// This function should not be called by code. It's a internal function
// that's called recursively.
VannaScriptLoader.CheckQueue = function() 
{
	// Check if the queue is not empty.
	if ( this.Queue.length > 0 )
	{
		this.IsLoading = true ;
		
		// Get the first item in the queue
		var sScriptPath = this.Queue[0] ;
		
		// Removes the first item from the queue
		var oTempArray = new Array() ;
		for ( i = 1 ; i < this.Queue.length ; i++ )
			oTempArray[ i - 1 ] = this.Queue[ i ] ;
		this.Queue = oTempArray ;
		
		this.LoadFile( sScriptPath ) ;
	}
	else
	{
		this.IsLoading = false ;
		
		// Call the "OnEmpty" event.
		if ( this.OnEmpty ) 
			this.OnEmpty() ;
	}
}

VannaScriptLoader.LoadFile = function( filePath ) 
{
	//window.status = ( 'Loading ' + filePath + '...' ) ;

	// Dynamically load the file (it can be a CSS or a JS)
	var e ;
	
	// If it is a CSS
	if ( filePath.lastIndexOf( '.css' ) > 0 )
	{
		e = document.createElement( 'LINK' ) ;
		e.rel	= 'stylesheet' ;
		e.type	= 'text/css' ;
	}
	// It it is a JS
	else
	{
		e = document.createElement( "script" ) ;
			e.type	= "text/javascript" ;
	}
	
	// Add the new object to the HEAD.
	document.getElementsByTagName("head")[0].appendChild( e ) ; 

	// Start downloading it.
	if ( e.tagName == 'LINK' )
	{
		// IE must wait for the file to be downloaded.
		if ( 1)//VannaBrowserInfo.IsIE )
			e.onload = VannaScriptLoader_OnLoad ;
		// Gecko doens't fire any event when the CSS is loaded, so we 
		// can't wait for it.
		else
			VannaScriptLoader.CheckQueue() ;
			
		e.href = filePath ;
	}
	else
	{
		// Gecko fires the "onload" event and IE fires "onreadystatechange"
		e.onload = e.onreadystatechange = VannaScriptLoader_OnLoad ;
		e.src = filePath ;
	}
}

function VannaScriptLoader_OnLoad()
{
	// Gecko doesn't have a "readyState" property
	if ( this.tagName == 'LINK' || !this.readyState || this.readyState == 'loaded' ) {
		// Load the next script available in the queue
		VannaScriptLoader.CheckQueue() ;
	}
}

for (var i=0;i<vnn_onloadscripts.length;i++) VannaScriptLoader.LoadFile(vnn_onloadscripts[i]);
