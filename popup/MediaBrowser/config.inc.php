<?


$basePath = dirname( __FILE__ );
$BASE_DIR = substr($basePath,0,strlen($basePath)-18);
include_once($BASE_DIR."configuration.php");

$BASE_URL = $simConfig_live_site;


$BASE_ROOT = "files/File"; 

if (trim($BASE_ROOT)) {if ($subfolder) $BASE_ROOT=$BASE_ROOT."/".$subfolder;}
else  {if ($subfolder) $BASE_ROOT=$BASE_ROOT."".$subfolder;}
$EXTS=array();
$EXTS['video']=array('avi','mpg','wmv','mpeg','Avi','Mpg','Wmv','Mpeg','AVI','MPG','WMV','MPEG');
$EXTS['audio']=array('mp3','Mp3','MP3','wav','Wav','WAV');


$allowedExt=array();

//In safe mode, directory creation is not permitted.

$SAFE_MODE = false;

$replaceCharsArray=array(
"č"=>"c","ć"=>"c","š"=>"s","đ"=>"d","ž"=>"z","Č"=>"C","Ć"=>"C","Š"=>"S","Đ"=>"D","Ž"=>"Z",
"-"=>"_",","=>"_"," "=>"_","["=>"_","]"=>"_","("=>"_",")"=>"_","{"=>"_","}"=>"_","&"=>"_",
"="=>"_","+"=>"_","#"=>"_","$"=>"_","%"=>"_","!"=>"_","?"=>"_","\""=>"_"
);
$allowedChars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_./";

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