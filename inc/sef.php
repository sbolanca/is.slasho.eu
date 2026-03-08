<?

function vnnConvertVannaLink($link,$lang,$isAdmin=false) {
	$lnk_arr=explode('|',$link);
	return ($isAdmin ? 'index.php':'index.php').'?'.$lnk_arr[3];
}


function vnnConvertVannaLinks($tekst,$lang,$isAdmin=false) {

	return preg_replace ("/({vnn_link )(\S+)(\})/e", 
           //   "vnnConvertVannaLink('\\2')", 
		   		 "vnnConvertVannaLink('\\2','$lang',".($isAdmin ? 'true': 'false').")",
              $tekst);
}






?>