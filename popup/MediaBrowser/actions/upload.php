<?



				
		setDir();





if(isset($_FILES['upload']) && is_array($_FILES['upload'])) {

	$dirPathPost = getParam($_POST,'dirPath');
	
	

	do_upload($_FILES['upload'], $BASE_DIR.$BASE_ROOT.$dirPathPost.'/');
}

function do_upload($file, $dest_dir) {
	global $mediaType,$allowedExt,$BASE_DIR,$clearUploads;
	

	
	if(is_file($file['tmp_name'])) 
	{
		//var_dump($file); echo "DIR:$dest_dir";
		$newname=clearFilename($file['name']);
		
		$ext = preg_replace("/^.+\\.([^.]+)$/i", "\\1", $newname);

		//if (in_array($ext,$allowedExt)) {
			move_uploaded_file($file['tmp_name'], $dest_dir.$newname);
		//	chmod($dest_dir.$newname,777);
			
			
		//}
		
	}



	$clearUploads = true;
}



?>