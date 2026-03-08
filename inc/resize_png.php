<?

function resizepng($imgnamesrc,$imgnamedest,$percent,$resample) {
    $im = @imagecreatefrompng ($imgnamesrc); /* Attempt to open */
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
		imagepng($im2,$imgnamedest);
	}
    return $im2;
}

function resizegif($imgnamesrc,$imgnamedest,$percent,$resample) {
    $im = @imagecreatefromgif ($imgnamesrc); /* Attempt to open */
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
		imagepng($im2,$imgnamedest);
	}
    return $im2;
}

function make_img_from_png($imgnamesrc) {
// vraæa image objekt na osnovu zadanog fajla
    $im_created = @imagecreatefrompng ($imgnamesrc); /* Attempt to open */
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

function make_img_from_gif($imgnamesrc) {
// vraæa image objekt na osnovu zadanog fajla
    $im_created = @imagecreatefromgif ($imgnamesrc); /* Attempt to open */
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


function resizepng_to_width($imgnamesrc,$imgnamedest,$width,$resample) {
    $im = @imagecreatefrompng ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
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
		imagepng($im2,$imgnamedest);
	} else $im2=false;
    return $im2;
}

function resizegif_to_width($imgnamesrc,$imgnamedest,$width,$resample) {
    $im = @imagecreatefromgif ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgnamesrc", $tc);
    } else if (intval($width)<imagesy($im)) {
		$im2=imagecreate($width,imagesy($im)*$width/imagesx($im));
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		imagepng($im2,str_replace('.gif','.png',$imgnamedest));
		unlink($imgnamedest);
	} else $im2=false;
    return $im2;
}

function resizepng_to_height($imgnamesrc,$imgnamedest,$height,$resample) {
    $im = @imagecreatefrompng ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    }
	else {
		$im2=imagecreatetruecolor(imagesx($im)*$height/imagesy($im),$height);
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		imagepng($im2,$imgnamedest);
	}
    return $im2;
}

function resizegif_to_height($imgnamesrc,$imgnamedest,$height,$resample) {
    $im = @imagecreatefromgif ($imgnamesrc); /* Attempt to open */
    if (!$im) { /* See if it failed */
        $im2  = imagecreate (150, 30); /* Create a blank image */
        $bgc = imagecolorallocate ($im2, 255, 255, 255);
        $tc  = imagecolorallocate ($im2, 0, 0, 0);
        imagefilledrectangle ($im2, 0, 0, 150, 30, $bgc);
        /* Output an errmsg */
        imagestring ($im2, 1, 5, 5, "Error loading $imgname", $tc);
    }
	else {
		$im2=imagecreatetruecolor(imagesx($im)*$height/imagesy($im),$height);
		if ($resample) imagecopyresampled($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		else imagecopyresized($im2,$im,0,0,0,0,imagesx($im2),imagesy($im2),imagesx($im),imagesy($im));
		imagepng($im2,$imgnamedest);
	}
    return $im2;
}

function resizepng_to_limit($imgnamesrc,$imgnamedest,$maxw,$maxh,$quality,$resample) {
// ako je slika 'imgnamesrc' po nekoj od dimezija veæa od limita (maxw,maxh) slika se limitira na taj limit,
// a druga dimenzija se odreðuje proporcionalno ('constrain') 
 
  $im_resulted=make_img_from_png($imgnamesrc);
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
 	$imr=resizepng_to_width($imgnamesrc,$imgnamedest,$pomw,$quality,$resample);
  return $imr;
}
function croppng_to_size($imgnamesrc,$imgnamedest,$width,$height,$cx,$cy,$quality,$resample) {
   	$im = @imagecreatefrompng ($imgnamesrc); /* Attempt to open */
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
		imagepng($im2,$imgnamedest,$quality);
  
  }
    return $im2;
}
function resizegif_to_limit($imgnamesrc,$imgnamedest,$maxw,$maxh,$quality,$resample) {
// ako je slika 'imgnamesrc' po nekoj od dimezija veæa od limita (maxw,maxh) slika se limitira na taj limit,
// a druga dimenzija se odreðuje proporcionalno ('constrain') 
 
  $im_resulted=make_img_from_gif($imgnamesrc);
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
 	$imr=resizegif_to_width($imgnamesrc,$imgnamedest,$pomw,$quality,$resample);
  return $imr;
}
function cropgif_to_size($imgnamesrc,$imgnamedest,$width,$height,$cx,$cy,$quality,$resample) {
   	$im = @imagecreatefromgif ($imgnamesrc); /* Attempt to open */
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
		imagepng($im2,$imgnamedest,$quality);
  
  }
    return $im2;
}


function create_thumb_from_img($im,$imgnamedest,$xpos,$ypos,$width,$height,$thmbw,$thmbh,$resample) {
// kreira thumbnail tako da izreže isjeèak zadanih dimenzija (width,height) sa zadane pozicije (xpos,ypos),
// smanji ga na dimenziju thumbnaila (thumbw,thumbh) i snimi u datoteku imgnamedest
	$im2=imagecreatetruecolor($thmbw,$thmbh);
	if ($resample) {
		$im3=imagecreatetruecolor($width,$height);
		imagecopyresized($im3,$im,0,0,$xpos,$ypos,$width,$height,$width,$height);
		imagecopyresampled($im2,$im3,0,0,0,0,$thmbw,$thmbh,$width,$height);
	}
	else imagecopyresized($im2,$im,0,0,$xpos,$ypos,$thmbw,$thmbh,$width,$height);
	imagepng($im2,$imgnamedest);
    return $im2;
}


?>

