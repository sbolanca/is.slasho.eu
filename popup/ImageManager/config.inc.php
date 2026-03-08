<?


$basePath = dirname( __FILE__ );
$bp=substr($basePath,0,strlen($basePath)-18);
include_once($bp."/configuration.php");
include_once $bp.'/images.conf.php';

	$subfolder=$_GET['subfolder'];
	$model=$_GET['model'];
	if (!$model) $model='default';

$BASE_DIR = $simConfig_absolute_path;

$BASE_URL = $simConfig_live_site;


$BASE_ROOT = 'files/Image'; 
$subfolder=$IMGCONF[$model][3] ? $IMGCONF[$model][3]."/".$subfolder : $subfolder;
	
if ($subfolder) $BASE_ROOT=$BASE_ROOT."/".$subfolder;

//In safe mode, directory creation is not permitted.

$SAFE_MODE = false;

$replaceCharsArray=array(
"č"=>"c",
"ć"=>"c",
"š"=>"s",
"đ"=>"d",
"ž"=>"z",
"Č"=>"C",
"Ć"=>"C",
"Š"=>"S",
"Đ"=>"D",
"Ž"=>"Z"
);
$allowedChars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_./";

//************************** END CONFIGURATION *****************************//



$IMG_ROOT = $BASE_ROOT;



if(strrpos($BASE_DIR, '/')!= strlen($BASE_DIR)-1) 

	$BASE_DIR .= '/';



if(strrpos($BASE_URL, '/')!= strlen($BASE_URL)-1) 

	$BASE_URL .= '/';



//Built in function of dirname is faulty

//It assumes that the directory nane can not contain a . (period)

function dir_name($dir) 

{

	$lastSlash = intval(strrpos($dir, '/'));

	if($lastSlash == strlen($dir)-1){

		return substr($dir, 0, $lastSlash);

	}

	else

		return dirname($dir);

}

function clearFilename($n) {
	global $replaceCharsArray,$allowedChars;
	foreach(array_keys($replaceCharsArray) as $k) $n=str_replace($k,$replaceCharsArray[$k],$n);
	$i=0;
	while($i<strlen($n)) {
		$char=substr($n,$i,1);
		if (!substr_count($allowedChars,$char)) $n=str_replace($char,'',$n);
		else $i++;
	}
	return $n;
}

?>