<?


		$url=getParam($_POST,'url');
		$file=str_replace($BASE_URL,$BASE_DIR,$url);
	 	$thumbQuality=getParam($_POST,'thumbQuality',90);
		image_rotate($file,3,$thumbQuality);		
		
		setDir();



?>