<?

function resizejpeg($imgnamesrc,$imgnamedest,$percent,$quality,$resample) {
    $im = @imagecreatefromjpeg ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    }
	else {
		$im2=imagecreatetruecolor(imagesx($im)*$percent/100,imagesy($im)*$percent/100);
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		
		imagejpeg($im2,$imgnamedest,$quality);


		
	}
    return $im2;
}

function make_img_from_jpeg($imgnamesrc) {
// vraæa image objekt na osnovu zadanog fajla
    $im_created = @imagecreatefromjpeg ($imgnamesrc); /* Attempt to open */
    if (!$im_created) { /* See if it failed */
        $im_resulted  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im_resulted, 255, 255, 255);
        $tc  = imagecolorallocate ($im_resulted, 0, 0, 0);
        imagefilledrectangle ($im_resulted, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im_resulted, 1, 5, 5, "Error loading $imgname", $tc);
    }
	else {
		$im_resulted=imagecreatetruecolor(imagesx($im_created),imagesy($im_created));
	}
    return $im_resulted;
}

/*
  echo $ww." x ".$hh."<br>".$pomw." x ".$pomh;
  die();
  
*/
function resizejpeg_to_width($imgnamesrc,$imgnamedest,$width,$quality,$resample) {
    $im = @imagecreatefromjpeg ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
		echo '0'; die();
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    } else if (intval($width)<imagesx($im)) {
		
		$im2=imagecreatetruecolor($width,imagesy($im)*$width/imagesx($im));
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		imagejpeg($im2,$imgnamedest,$quality);
	} else $im2=false;
    return $im2;
}

function resizejpeg_to_height($imgnamesrc,$imgnamedest,$height,$quality,$resample) {
    $im = @imagecreatefromjpeg ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
     } else if (intval($height)<imagesy($im)) {
		$im2=imagecreatetruecolor(imagesx($im)*$height/imagesy($im),$height);
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		imagejpeg($im2,$imgnamedest,$quality);
	} else $im2=false;
    return $im2;
}

function resizejpeg_to_limit($imgnamesrc,$imgnamedest,$maxw,$maxh,$quality,$resample) {
// ako je slika 'imgnamesrc' po nekoj od dimezija veæa od limita (maxw,maxh) slika se limitira na taj limit,
// a druga dimenzija se odreðuje proporcionalno ('constrain') 
 
  $im_resulted=make_img_from_jpeg($imgnamesrc);
  $ww=imagesx($im_resulted);
  $hh=imagesy($im_resulted);
  $omjer=$ww/$hh;
  $tomjer=$maxw/$maxh;
  if ($omjer>$tomjer) {
  	
  	$pomw=$maxw;
  	$pomh=intval($hh*$pomw/$ww);
  } else {
  	
  	$pomh=$maxh;
  	$pomw=intval($ww*$pomh/$hh);
  }

  	$imr=false;
 	$imr=resizejpeg_to_width($imgnamesrc,$imgnamedest,$pomw,$quality,$resample);
  return $imr;
}
function cropjpeg_to_size($imgnamesrc,$imgnamedest,$width,$height,$cx,$cy,$quality,$resample) {
   	$im = @imagecreatefromjpeg ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    } else {
	  $ww=imagesx($im); //širina originala
	  $hh=imagesy($im); //visina originala
	  $omjer=$ww/$hh; //omjer originala
	  $tomjer=$width/$height; //omjer thumba
	  if ($omjer>$tomjer) { //ako kropamo po širini
		$y=0;
		$sheight=$hh;
		$swidth=$hh*$tomjer;
		$x=$cx>-1?$cx:($ww-$swidth)/2; //na pola širine

	  } else { //ako kropamo po visini
		$x=0;
		$swidth=$ww;
		$sheight=$ww/$tomjer;
		$y=$cy>-1?$cy:($hh-$sheight)/2; //na pola visine

 	 }

	 

	
		$im2=imagecreatetruecolor($width,$height);
		if ($resample)
			imagecopyresampled($im2,$im,0,0,intval($x),intval($y),intval($width),intval($height),intval($swidth),intval($sheight));
			//imagecopyresampled($im2,$im,0,0,0,0,intval($ww),intval($hh),intval($ww),intval($hh));
		else imagecopyresized($im2,$im,0,0,intval($x),intval($y),intval($width),intval($height),intval($swidth),intval($sheight));
		imagejpeg($im2,$imgnamedest,$quality);
  
  }
    return $im2;
}
function create_thumb_from_img($im,$imgnamedest,$xpos,$ypos,$width,$height,$thmbw,$thmbh,$quality,$resample) {
// kreira thumbnail tako da izreže isjeèak zadanih dimenzija (width,height) sa zadane pozicije (xpos,ypos),
// smanji ga na dimenziju thumbnaila (thumbw,thumbh) i snimi u datoteku imgnamedest
	$im2=imagecreatetruecolor($thmbw,$thmbh);
	if ($resample) {
		$im3=imagecreatetruecolor($width,$height);
		imagecopyresized($im3,$im,0,0,$xpos,$ypos,$width,$height,$width,$height);
		imagecopyresampled($im2,$im3,0,0,0,0,$thmbw,$thmbh,$width,$height);
	}
	else imagecopyresized($im2,$im,0,0,$xpos,$ypos,$thmbw,$thmbh,$width,$height);
	imagejpeg($im2,$imgnamedest,$quality);
    return $im2;
}

function jpeg_rotate_custom($imgnamesrc,$deg,$quality) {
    $im = @imagecreatefromjpeg ($imgnamesrc); 
    if (!$im) { 
        $im2  = imagecreate (150, 30); 
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    }
	else {
		$im2 = imagerotate($im, $deg, 0) ;
		imagejpeg($im2,$imgnamesrc,$quality);
	}
    return $im2;
}


function jpeg_rotate($src, $count = 1, $quality = 95)
{
   if (!file_exists($src)) {
       return false;
   }

   list($w, $h) = getimagesize($src);

   if (($in = imageCreateFromJpeg($src)) === false) {
       echo "Failed create from source<br>";
       return false;
   }

   $angle = 360 - ((($count > 0 && $count < 4) ? $count : 0 ) * 90);

   if ($w == $h || $angle == 180) {
       $out = imageRotate($in, $angle, 0);
   } elseif ($angle == 90 || $angle == 270) {
       $size = ($w > $h ? $w : $h);
       // Create a square image the size of the largest side of our src image
       if (($tmp = imageCreateTrueColor($size, $size)) == false) {
           echo "Failed create square trueColor<br>";
           return false;
       }

       // Exchange sides
       if (($out = imageCreateTrueColor($h, $w)) == false) {
           echo "Failed create trueColor<br>";
           return false;
       }

       // Now copy our src image to tmp where we will rotate and then copy that to $out
       imageCopy($tmp, $in, 0, 0, 0, 0, $w, $h);
       $tmp2 = imageRotate($tmp, $angle, 0);

       // Now copy tmp2 to $out;
       imageCopy($out, $tmp2, 0, 0, ($angle == 270 ? abs($w - $h) : 0), 0, $h, $w);
       imageDestroy($tmp);
       imageDestroy($tmp2);
   } elseif ($angle == 360) {
       imageDestroy($in);
       return true;
   }

   imageJpeg($out, $src, $quality);
   imageDestroy($in);
   imageDestroy($out);
   return true;
}

?>

