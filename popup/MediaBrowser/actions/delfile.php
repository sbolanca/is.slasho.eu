<?


	

	$del_image = dir_name($BASE_DIR).'/'.$BASE_ROOT.getParam($_REQUEST,'dirPath')."/".getParam($_REQUEST,'delFile');

	//$del_thumb = dir_name($del_image).'.'.basename($del_image);
	if(is_file($del_image)) {
		unlink($del_image);	
	} 
				
	setDir();



?>