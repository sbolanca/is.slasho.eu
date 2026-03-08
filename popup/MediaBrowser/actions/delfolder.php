<?


	

	$_df=getParam($_REQUEST,'delFolder');
	$del_folder = $BASE_DIR.$_df;
	if($_df && is_dir($del_folder)) { 
		rm_all_dir($del_folder);
		$refresh_dirs = true;
	}
	$BASE_DIR=str_replace("//","/",$BASE_DIR);
	$BASE_ROOT=str_replace("//","/",$BASE_ROOT);
	$par=getParent(substr($_df,strlen($BASE_ROOT))."/");

	setDir($par);

function rm_all_dir($dir) 
{
	if(is_dir($dir)) 	{
		$d = @dir($dir);
		
		while (false !== ($entry = $d->read())) {
			if($entry != '.' && $entry != '..') {
				$node = $dir.'/'.$entry;
				if(is_file($node))  unlink($node);	
				else if(is_dir($node)) rm_all_dir($node);				
			}
		}
		$d->close();
		rmdir($dir);
		//echo "<br>-".$dir;
	}
}
				
	



?>