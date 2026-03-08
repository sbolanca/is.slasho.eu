<?

function getParam(&$arr,$n,$def='') {
	if (isset($arr[$n])) $ret=$arr[$n];
	else $ret=$def;
	return $ret;
}

function setDir($d='&&&&&&&&&&&&&&&&&&&&&&&&&&') {
	global $IMG_ROOT;
	if ($d=='&&&&&&&&&&&&&&&&&&&&&&&&&&') $dirParam =	getParam($_REQUEST,'dirPath');
	else $dirParam=$d;
	if(strlen($dirParam) > 0) 
	{
		if(substr($dirParam,0,1)=='/') 
			$IMG_ROOT .= $dirParam;		
		else
			$IMG_ROOT = $dirParam;			
	}	
	if(strrpos($IMG_ROOT, '/')!= strlen($IMG_ROOT)-1) 
	$IMG_ROOT .= '/';
	$IMG_ROOT=str_replace("//","/",$IMG_ROOT);
}
function getParent($pth) {
	$paths = explode('/', $pth);
	$upDirPath = '/';
	for($i=0; $i<count($paths)-2; $i++) 
	{
		$path = $paths[$i];
		if(strlen($path) > 0) 
		{
			$upDirPath .= $path.'/';
		}
	}
	return($upDirPath);
}

?>