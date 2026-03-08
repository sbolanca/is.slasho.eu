/*
 * jsound 1.0.2
 *
 * JSound, a jQuery plugin for playing sounds on any html tag element
 *
 * Copyright (c) 2012 Ayman Teryaki
 * Havalite CMS (http://havalite.com/)
 * MIT style license, FREE to use, alter, copy, sell, and especially ENHANCE
 * Flashplayer http://www.alsacreations.fr/dewplayer-en.html
 *
 */
var soundElem = 'a, p, input'; // allow sounds for these tags
var allElems = false; // if its true the defined tag will have automatically the following sounds
var aHover = 'beep'; // sounds if allElems = true		
var aLeave = 'select'; // ***
var aClick = 'page'; // ***
var aFocusIn = 'page'; // ***
var aFocusOut = 'select'; // ***
/* The mp3 flash player "dewplayer-mini.swf" must be in same folder */
var allowFlash = false; // if true, the audio tag will be replaced with flash player for only mp3 sounds
if(!isFlashEnabled()) allowFlash = false;

function playFx(file) {
	$('#Sound').html( playSound('files/Audio/fx/'+file, 0));
}
function playSound(file, loop){
	var mp3File = file + ".mp3";
	if(loop){ loop = ' loop="loop"'; flashLoop = 1; }
	else flashLoop = 0;
	
	if(allowFlash){
		return '<object class="flashPlayer" type="application/x-shockwave-flash" data="dewplayer-mini.swf" width="160" height="20" id="dewplayer" name="dewplayer"> <param name="wmode" value="transparent" /><param name="movie" value="dewplayer-mini.swf" /> <param name="flashvars" value="mp3=' + mp3File + '&amp;autostart=1&amp;autoreplay=' + flashLoop + '" /> </object>';
	}
	else{
		var oggFile = file + ".ogg"; 
		return '<audio autoplay="autoplay" ' + loop + '><source src="' + mp3File + '" type="audio/mpeg"><source src="' + oggFile + '" type="audio/ogg"></audio>'
	}
}

//checks if flash is installed/enabled on the browser
function isFlashEnabled(){
    var hasFlash = false;
    try{
        var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
        if(fo) hasFlash = true;
    }
    catch(e){
        if(navigator.mimeTypes ["application/x-shockwave-flash"] != undefined) hasFlash = true;
    }
    return hasFlash;
}

function stopSounds(){ $('audio').remove(); $('.flashPlayer').remove(); }

$(document).ready( function(){
	
	var apCode = '<div id="audioContainer" style="position:absolute; top:-300px;">'+
		'<span id="Sound"></span>'+
		'<span id="hoverSound"></span>'+
		'<span id="clickSound"></span>'+
		'<span id="leaveSound"></span>'+
		'<span id="moveSound"></span>'+
		'<span id="focusInSound"></span>'+
		'<span id="focusOutSound"></span>'+
		'<span id="startSound"></span>'+
		'</div>';
	
	$('body').append(apCode);

	$(soundElem).each(function(){
		if(allElems){
			if(aHover || aLeave){
				$(this).hover(
					function(){ $('#hoverSound').html( playSound(aHover)); },
					function(){  $('#leaveSound').html( playSound(aLeave)); }
				);
			}
			if(aClick){
				$(this).click(function(){ $('#clickSound').html( playSound(aClick)); });
			}
			
			if(aFocusIn || aFocusOut){
				$(this).focusin(function(){ $('#focusInSound').html( playSound(aFocusIn)); });
				$(this).focusout(function(){ $('#focusOutSound').html( playSound(aFocusOut)); });
			}
		}
		else{
			if($(this).attr('hoverSound') || $(this).attr('leaveSound')){
				var audioFile1 = $(this).attr('hoverSound');
				var audioFile2 = $(this).attr('leaveSound');
				$(this).hover(
					function(){ $('#hoverSound').html( playSound(audioFile1)); },
					function(){ $('#leaveSound').html( playSound(audioFile2)); }
				);
			}
			
			if($(this).attr('clickSound')){
				var audioFile = $(this).attr('clickSound');
				$(this).mousedown(function(){ $('#clickSound').html( playSound(audioFile)); });
			}
			
			if($(this).attr('moveSound')){
				var audioFile = $(this).attr('moveSound');
				$(this).mousemove(function(){ $('#moveSound').html( playSound(audioFile)); });
			}
			
			if($(this).attr('focusInSound') || $(this).attr('focusOutSound')){
				var audioFile1 = $(this).attr('focusInSound');
				var audioFile2 = $(this).attr('focusOutSound');
				$(this).focusin(function(){ $('#focusInSound').html( playSound(audioFile1)); });
				$(this).focusout(function(){ $('#focusOutSound').html( playSound(audioFile2)); });
			}
			
			if($(this).attr('startSound')){
				var audioFile = $(this).attr('startSound');
				var loopit = false;
				if($(this).attr('loopSound')) loopit = true;
				$(this).ready(function(){ 
					$('#startSound').html( playSound(audioFile, loopit)); 
				});
			}
		}
	});	
});
