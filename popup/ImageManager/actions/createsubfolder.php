<?



	$subfolder_name =	clearFilename(getParam($_REQUEST,'subfolder_name'));
	
	$dirpth=substr(getParam($_REQUEST,'dirPath'),1);
	if ($dirpth=="") $newdir=$subfolder_name;
	else $newdir=$dirpth."/".$subfolder_name;

	
	
	if(strlen($newdir) >0) 
	{
		$folder = $BASE_DIR.$IMG_ROOT.$newdir;
		if(!is_dir($folder) && !is_file($folder))
		{
			mkdir($folder,0777);	
			chmod($folder,0777);
			$refresh_dirs = true;
		}
	}

	setDir();			




?>