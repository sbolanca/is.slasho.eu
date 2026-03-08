<?


		global $BASE_URL, $BASE_DIR;
	 	$url=getParam($_POST,'url');
		$file=str_replace($BASE_URL,$BASE_DIR,$url);
	 	$thumbWidth=getParam($_POST,'thumbWidth',0);
	 	$thumbHeight=getParam($_POST,'thumbHeight',0);
	 	$offsetX=getParam($_POST,'offsetX',-1);
	 	$offsetY=getParam($_POST,'offsetY',-1);
		$thumbQuality=getParam($_POST,'thumbQuality',75);
		$thumbFolder=getParam($_POST,'thumbFolder','thumbs');
		$resizeOpt=getParam($_POST,'resizeOpt','w');
		//echo $resizeOpt;
		make_thumb($file,$thumbWidth,$thumbHeight,$thumbQuality,$thumbFolder,$resizeOpt,$offsetX,$offsetY);
				
		setDir();



?>