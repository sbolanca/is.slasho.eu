<?



				
		setDir();





if(isset($_FILES['upload']) && is_array($_FILES['upload'])) {

	$dirPathPost = getParam($_POST,'dirPath');
	
	//$makeThumb=$_POST['makeThumb'];
	if (getParam($_POST,'makeThumb')=='make') $makeThumb=true;
	else $makeThumb=false;
		
	

	do_upload($_FILES['upload'], $BASE_DIR.$BASE_ROOT.$dirPathPost.'/',$makeThumb);
}

function do_upload($file, $dest_dir,$makeThumb) {
	global $clearUploads,$BASE_DIR ;
	$allowedExt=array('jpg','jpeg','gif','png');
	if(is_file($file['tmp_name'])) 
	{
		//var_dump($file); echo "DIR:$dest_dir";
		$newname=clearFilename($file['name']);
		move_uploaded_file($file['tmp_name'], $dest_dir.$newname);
		
		$parts = pathinfo($dest_dir.$newname);
		switch (strtolower($parts['extension'])) {
					case 'jpg': case 'jpeg': case 'gif': case 'png':
						after_upload($dest_dir,$newname,$makeThumb);
						break;
					case 'zip': 
							$unzip=getParam($_POST,'unzip','');
							if ($unzip=="yes") {
								require_once ($BASE_DIR."inc/lib/pclzip.lib.php");
								$zipfile = new PclZip($dest_dir.$newname);
								$ret = $zipfile->extract(PCLZIP_OPT_PATH,$dest_dir);
	
								if ($ret && count($ret)) {
									foreach ($ret as $r) if (!intval($r['folder'])){
										$path_parts = pathinfo($r['filename']);
										if (in_array(strtolower($path_parts['extension']),$allowedExt)) {
												 $nn=clearFilename($r['stored_filename']);
												 if (!($nn==$r['stored_filename'])) rename ( $r['filename'],$dest_dir.$nn);
												 after_upload($dest_dir,$nn,$makeThumb); 
										} else unlink ($r['filename']);									
									}
								}							
								unlink ($dest_dir.$newname);
							}
							break;
					default: unlink ($dest_dir.$newname);
		}
	}



	$clearUploads = true;
}

function after_upload($dest_dir,$newname,$makeThumb) {
		global $IMGCONF,$IMGTHCONF;
		$model=getParam($_POST,'model');
		$r=getParam($_POST,'resize');
		$rs=intval(getParam($_POST,'resizevalue',0));
		$rs2=intval(getParam($_POST,'resizevalue2',0));
		if (($r=='yes') && $rs) {
			$thumbQuality=intval(getParam($_POST,'thumbQuality',90));
			make_thumb($dest_dir.$newname,$rs,$rs2,$thumbQuality,'','maxvalue');
			$newname=str_replace('.gif','.png',$newname);				
		} 
		//chmod($dest_dir.$newname, 0666);
		
		if ($makeThumb){
				$thumbWidth=$_POST['thumbWidth'];
				$thumbHeight=$_POST['thumbHeight'];
				$thumbQuality=$_POST['thumbQuality'];
				$thumbFolder=$_POST['thumbFolder'];
				$resizeOpt=$_POST['resizeOpt'];
				make_thumb($dest_dir.$newname,$thumbWidth,$thumbHeight,$thumbQuality,$thumbFolder,$resizeOpt);
				if ((count($IMGCONF[$model])>4) && trim($IMGCONF[$model][4])) 
					foreach(explode("|",$IMGCONF[$model][4]) as $m) {
						$md=$IMGTHCONF[$m];
						make_thumb($dest_dir.$newname,$md[1],$md[2],$thumbQuality,$md[0],$md[3]);
					
				}
		}
		
}

?>