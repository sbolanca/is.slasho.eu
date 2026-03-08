<?


		global $BASE_URL, $BASE_DIR;
	 	$url=getParam($_POST,'url');
		$file=str_replace($BASE_URL,$BASE_DIR,$url);
	 	$thumbsize=getParam($_POST,'thumbSize',0);
	 	$thumbsizeh=getParam($_POST,'thumbSizeh',0);
		$thumbQuality=getParam($_POST,'thumbQuality',75);
		$thumbFolder=getParam($_POST,'thumbFolder','thumbs');
		$resizeOpt=getParam($_POST,'resizeOpt','w');
		//echo $resizeOpt;
		crop($file,$thumbsize,$thumbsizeh,$thumbQuality,$thumbFolder,$resizeOpt);
				
		setDir();



?>