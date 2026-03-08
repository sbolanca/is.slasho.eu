<?

function getParam(&$arr,$n,$def='') {
	if (isset($arr[$n])) $ret=$arr[$n];
	else $ret=$def;
	return $ret;
}

function setDir($d='&&&&&&&&&&&&&&&&&&&&&&&&&&') {
	global $IMG_ROOT;
	if ($d=='&&&&&&&&&&&&&&&&&&&&&&&&&&') $dirParam =	getParam($_REQUEST,'dirPath');
	else $dirParam=$d;
	if(strlen($dirParam) > 0) 
	{
		if(substr($dirParam,0,1)=='/') 
			$IMG_ROOT .= $dirParam;		
		else
			$IMG_ROOT = $dirParam;			
	}	
	if(strrpos($IMG_ROOT, '/')!= strlen($IMG_ROOT)-1) 
	$IMG_ROOT .= '/';
}
function getParent($pth) {
	$paths = explode('/', $pth);
	$upDirPath = '/';
	for($i=0; $i<count($paths)-2; $i++) 
	{
		$path = $paths[$i];
		if(strlen($path) > 0) 
		{
			$upDirPath .= $path.'/';
		}
	}
	return($upDirPath);
}
function image_rotate($file,$deg,$quality) {
	global $BASE_DIR,$refresh_dirs;
	
	$extpos=strrpos($file,".");
	$ext=strtolower(substr($file,$extpos+1));
	$ext2=$ext;
	if ($ext2=="gif") $ext2="png";		
	
	if ($ext=="png") {
		include_once ($BASE_DIR."inc/resize_png.php");
		
	} else if ($ext=="gif") {
		include_once ($BASE_DIR."inc/resize_png.php");
	} else {
		include_once ($BASE_DIR."inc/resize_jpeg.php");
		jpeg_rotate($file,$deg,$quality); 
	}
}
function make_thumb($file, $thumbWidth,$thumbHeight, $thumbQuality, $thumbFolder, $resizeOpt,$cx=-1,$cy=-1) {
	global $BASE_DIR,$refresh_dirs;
	
	$extpos=strrpos($file,".");
	$ext=strtolower(substr($file,$extpos+1));
	$ext2=$ext;
	if ($ext2=="gif") $ext2="png";		
	
	
	$n1=strrpos($file,"\\");
	$n2=strrpos($file,"/");
	$ned=max($n1,$n2);
	$filename=substr($file,$ned+1);
	$path_velika=substr($file,0,$ned);
	$thumbdir=$path_velika."/".$thumbFolder;
	$path_mala=$thumbdir."/".$filename;
	if(!is_dir($thumbdir) && !is_file($thumbdir))
		{
			mkdir($thumbdir,0777);	
			chmod($thumbdir,0777);
			$refresh_dirs = true;
		}
		
		//echo $BASE_DIR;
	
	if ($ext=="png") {
		include_once ($BASE_DIR."inc/resize_png.php");
		switch ($resizeOpt) {
			case 'width': $slika_thumb_im=resizepng_to_width($file,$path_mala,$thumbWidth,true);break;
			case 'height': $slika_thumb_im=resizepng_to_height($file,$path_mala,$thumbHeight,true); break;
			case 'crop': $slika_thumb_im=croppng_to_size($file,$path_mala,$thumbWidth,$thumbHeight,$cx,$cy,true);break;
			default: $slika_thumb_im=resizepng_to_limit($file,$path_mala,$thumbWidth,$thumbHeight,true);
		}
	} else if ($ext=="gif") {
		include_once ($BASE_DIR."inc/resize_png.php");
		switch ($resizeOpt) {
			case 'width': $slika_thumb_im=resizegif_to_width($file,$path_mala,$thumbWidth,true);break;
			case 'height': $slika_thumb_im=resizegif_to_height($file,$path_mala,$thumbHeight,true); break;
			case 'crop': $slika_thumb_im=croppng_to_size($file,$path_mala,$thumbWidth,$thumbHeight,$cx,$cy,true);break;
			default: $slika_thumb_im=resizegif_to_limit($file,$path_mala,$thumbWidth,$thumbHeight,true);
		}
	} else {
		include_once ($BASE_DIR."inc/resize_jpeg.php");
		switch ($resizeOpt) {
			case 'width': $slika_thumb_im=resizejpeg_to_width($file,$path_mala,$thumbWidth,$thumbQuality,true);break;
			case 'height': $slika_thumb_im=resizejpeg_to_height($file,$path_mala,$thumbHeight,$thumbQuality,true); break;
			case 'crop': $slika_thumb_im=cropjpeg_to_size($file,$path_mala,$thumbWidth,$thumbHeight,$cx,$cy,$thumbQuality,true) ;break;
			default: $slika_thumb_im=resizejpeg_to_limit($file,$path_mala,$thumbWidth,$thumbHeight,$thumbQuality,true);
		}
	}
	

}
function crop($file, $thumbsize, $thumbsizeh, $thumbQuality, $thumbFolder, $resizeOpt) {
	global $BASE_DIR,$refresh_dirs;
	$extpos=strrpos($file,".");
	$ext=strtolower(substr($file,$extpos+1));
	$ext2=$ext;
	if ($ext2=="gif") $ext2="png";		
	
	
	$n1=strrpos($file,"\\");
	$n2=strrpos($file,"/");
	$ned=max($n1,$n2);
	$filename=substr($file,$ned+1);
	$path_velika=substr($file,0,$ned);
	$thumbdir=$path_velika."/".$thumbFolder;
	$path_mala=$thumbdir."/".$filename;
	if(!is_dir($thumbdir) && !is_file($thumbdir))
		{
			mkdir($thumbdir,0777);	
			chmod($thumbdir,0777);
			$refresh_dirs = true;
		}
		
		//echo $BASE_DIR;
	
	if ($ext=="png") {
		include_once ($BASE_DIR."inc/resize_png.php");
		switch ($resizeOpt) {
			case 'w': $slika_thumb_im=croppng_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true);break;
			case 'h': $slika_thumb_im=croppng_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true); break;
			default: $slika_thumb_im=croppng_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true);
		}
	} else if ($ext=="gif") {
		include_once ($BASE_DIR."inc/resize_png.php");
		switch ($resizeOpt) {
			case 'w': $slika_thumb_im=cropjgif_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true);break;
			case 'h': $slika_thumb_im=cropjgif_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true); break;
			default: $slika_thumb_im=cropjgif_to_size($file,$path_mala,$thumbsize,$thumbsizeh,true);
		}
	} else {
		include_once ($BASE_DIR."inc/resize_jpeg.php");
		switch ($resizeOpt) {
			case 'w': $slika_thumb_im=cropjpeg_to_size($file,$path_mala,$thumbsize,$thumbsizeh,$thumbQuality,true);break;
			case 'h': $slika_thumb_im=cropjpeg_to_size($file,$path_mala,$thumbsize,$thumbsizeh,$thumbQuality,true); break;
			default: $slika_thumb_im=cropjpeg_to_size($file,$path_mala,$thumbsize,$thumbsizeh,$thumbQuality,true);
		}
	}
	

}

?>