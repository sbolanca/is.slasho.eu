<?
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  $isValid = False; 
  if (!empty($UserName)) { 
     $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
// $Id: images.php, v 1.0.1 2004/04/16 13:53:30 bpfeifer Exp $
/**
* HTMLArea3 addon - ImageManager
* Based on Wei Zhuo's ImageManager
* @package Mambo Open Source
* @Copyright © 2004 Bernhard Pfeifer aka novocaine
* @ All rights reserved
* @ Mambo Open Source is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.1 $
**/
error_reporting (0);
$subfolder=isset($_GET['subfolder'])?$_GET['subfolder']:'';
$gp=isset($_GET['gp'])?$_GET['gp']:'';

$jsactions=array();
$onLoad='';
$refresh_dirs = false;
$clearUploads = false;

include 'config.inc.php';

include('include.php'); 

if(strrpos($IMG_ROOT, '/')!= strlen($IMG_ROOT)-1) 
	$IMG_ROOT .= '/';

$act=getParam($_REQUEST,'act','');
if (($act) && file_exists('actions/'.$act.'.php')) {
	include('actions/'.$act.'.php'); 
}

if(strrpos($IMG_ROOT, '/')!= strlen($IMG_ROOT)-1) 
	$IMG_ROOT .= '/';


function num_files($dir) 
{
	$total = 0;
	if(is_dir($dir)) {
		$d = @dir($dir);
		while (false !== ($entry = $d->read())) 		{
			if(substr($entry,0,1) != '.') {
				$total++;
			}
		}
		$d->close();
	}
	return $total;
}

function dirs($dir,$abs_path) 
{
	$d = dir($dir);
		//echo "Handle: ".$d->handle."<br>\n";
		//echo "Path: ".$d->path."<br>\n";
		$dirs = array();
		while (false !== ($entry = $d->read())) {
			if(is_dir($dir.'/'.$entry) && substr($entry,0,1) != '.') 
			{
				//dirs($dir.'/'.$entry, $prefix.$prefix);
				//echo $prefix.$entry."<br>\n";
				$path['path'] = $dir.'/'.$entry;
				$path['name'] = $entry;
				$dirs[$entry] = $path;
			}
		}
		$d->close();
	
		ksort($dirs);
		for($i=0; $i<count($dirs); $i++) 
		{
			$name = key($dirs);
			$current_dir = $abs_path.'/'.$dirs[$name]['name'];
			echo ", \"$current_dir\"\n";
			dirs($dirs[$name]['path'],$current_dir);
			next($dirs);
		}
}

function parse_size($size) 
{
	if($size < 1024) 
		return $size.' Bytes';	
	else if($size >= 1024 && $size < 1024*1024) 
	{
		return sprintf('%01.2f',$size/1024.0).' KB';	
	}
	else
	{
		return sprintf('%01.2f',$size/(1024.0*1024)).' MB';	
	}
}

function show_image($img, $file, $info, $size,$thumb,$big_info) 
{
	global $BASE_DIR, $BASE_URL,$BASE_ROOT, $newPath, $subfolder,$gp,$onLoad;

	$img_path = str_replace("//","/",dir_name($img));
	$img_file = basename($img);
	

	$img_url = $BASE_URL.str_replace("//","/",$img_path.'/'.$img_file);

	$filesize = parse_size($size);

if ($info[2]=='ZIP') {
?>
<div class="f" style="color:#aa0000;" onMouseOver="pviiClassNew(this,'f_h')" onMouseOut="pviiClassNew(this,'f')">
	<div class="fn" onClick="javascript:imageSelected('<? echo $img_url; ?>', '-', '-','<? echo $file; ?>','<? echo $thumb; ?>',0,0);"><? echo $file; ?></div>
	<div class="fs" onClick="javascript:imageSelected('<? echo $img_url; ?>', '-', '-','<? echo $file; ?>','<? echo $thumb; ?>',0,0);"><? echo $filesize; ?></div>
	<div class="del"><a href="images.php?act=delfile&delFile=<? echo $file; ?>&dirPath=<? echo $newPath; ?>&subfolder=<? echo $subfolder; ?>" onClick="return deleteImage('<? echo $file; ?>');"><img src="edit_trash.gif" width="15" height="15" border="0"/></a></div>
</div>
<? } else {
if (str_replace("//","/",$BASE_ROOT."/".$gp)==str_replace("//","/",$img))
	$onLoad=";javascript:imageSelected('$img_url',$info[0],$info[1],'$file','$thumb','$big_info[0]','$big_info[1]');topD.changeLoadingStatus('loadimage');";

?>
<div class="f" onMouseOver="pviiClassNew(this,'f_h')" onMouseOut="pviiClassNew(this,'f')">
	<div class="fn" onClick="javascript:imageSelected('<? echo $img_url; ?>', <? echo $info[0];?>, <? echo $info[1]; ?>,'<? echo $file; ?>','<? echo $thumb; ?>','<? echo $big_info[0]; ?>','<? echo $big_info[1]; ?>');topD.changeLoadingStatus('loadimage');"><? echo $file; ?></div>
	<div class="fs" onClick="javascript:imageSelected('<? echo $img_url; ?>', <? echo $info[0];?>, <? echo $info[1]; ?>,'<? echo $file; ?>','<? echo $thumb; ?>','<? echo $big_info[0]; ?>','<? echo $big_info[1]; ?>');topD.changeLoadingStatus('loadimage');"><? echo $filesize; ?></div>
	<div class="del"><a href="images.php?act=delfile&delFile=<? echo $file; ?>&dirPath=<? echo $newPath; ?>&subfolder=<? echo $subfolder; ?>" onClick="return deleteImage('<? echo $file; ?>');"><img src="edit_trash.gif" width="15" height="15" border="0"/></a></div>
</div>

<?
}
}

function show_dir($path, $dir) 
{
	global $newPath, $BASE_DIR, $BASE_URL, $subfolder;

	$num_files = num_files($BASE_DIR.$path);
?>
<div class="f" onMouseOver="pviiClassNew(this,'f_h')" onMouseOut="pviiClassNew(this,'f')">
	<div class="fld" onClick="topD.changeLoadingStatus('load'); linkaj('?act=updatedir&dirPath=<? echo $path; ?>');"> <img src="folder.gif" width="16" height="15" border="0"/></div>
	<div class="dn" onClick="topD.changeLoadingStatus('load'); linkaj('?act=updatedir&dirPath=<? echo $path; ?>');"><? echo $dir; ?></div>
	<div class="del"><a href="images.php?act=delfolder&delFolder=<? echo $path; ?>&subfolder=<? echo $subfolder; ?>" onClick="return deleteFolder('<? echo $dir; ?>',<? echo $num_files; ?>);"><img src="edit_trash.gif" width="15" height="15" border="0"/></a></div>
</div>

<?	
}

function draw_no_results() 
{
?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No files</div></td>
  </tr>
</table>
<?	
}

function draw_no_dir() 
{
	global $BASE_DIR, $BASE_ROOT;
?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="center" style="font-size:small;font-weight:bold;color:#CC0000;font-family: Helvetica, sans-serif;">Configuration problem: &quot;<? echo $BASE_DIR.$BASE_ROOT; ?>&quot; does not exist.</div></td>
  </tr>
</table>
<?	
}


function draw_table_header() {
	echo '<div class="list">';
}

function draw_table_footer() 
{
	echo '</div>';
}

?>
<html>
<head>
<title>Image Browser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="images.css" rel="stylesheet" type="text/css"/>

<?
	$dirPath = str_replace("//","/",str_replace($BASE_ROOT,'',$IMG_ROOT));
	$upDirPath= getParent($dirPath);

	

	$slashIndex = strlen($dirPath);
	$newPath = $dirPath;
	if($slashIndex > 1 && substr($dirPath, $slashIndex-1, $slashIndex) == '/')
	{
		$newPath = substr($dirPath, 0,$slashIndex-1);
	}
?>

<script language="JavaScript" type="text/JavaScript">
<!--




var topD = window.top;
function pviiClassNew(obj, new_style) { //v2.6 by PVII
  obj.className=new_style;
}

var pd=topD.document.getElementById('parentDir');
pd.value='<? echo $upDirPath; ?>';

function changeDir(newDir) 
{
	location.href = "ImageManager/images.php?dirPath="+newDir+"&subfolder=<? echo $subfolder; ?>";
}



<? if ($refresh_dirs) { ?>
function refreshDirs(dr) 
{
	var dp=topD.document.getElementById('dirPath');
	var allPaths =dp.options;
	var fields = ["/" <? dirs($BASE_DIR.$BASE_ROOT,'');?>];

	var newPath = "<? echo $newPath; ?>";

	while(allPaths.length > 0) 	{
		for(i=0; i<allPaths.length; i++) 		{
			allPaths[i]=null;	
		}		
	}

	for(i=0; i<fields.length; i++) 	{
		var newElem =	document.createElement("OPTION");
		var newValue = fields[i];
		newElem.text = newValue;
		newElem.value = newValue;

		if(newValue == newPath) 
			newElem.selected = true;	
		else
			newElem.selected = false;

		allPaths.add(newElem);
	}
}
refreshDirs();
<? } ?>

function imageSelected(filename, width, height, alt,thumb,bigw,bigh) 
{
	var topDoc=topD.document.getElementById('form1');
	topDoc.url.value = filename;
	var sfilename=filename.substr(filename.lastIndexOf('/')+1);
	topD.document.getElementById('f_width').innerHTML= width;
	topD.document.getElementById('f_height').innerHTML= height;
	topD.document.getElementById('filename').innerHTML = sfilename;
	if (width=='-') topD.showImage('ImageManager/zip.jpg');
	else topD.showImage(filename);
	topD.layerVis("goThumb", "visible");
		

}

function deleteImage(file) 
{
	if(confirm("Delete image \""+file+"\"?")) 
		return true;

	return false;
}

function deleteFolder(folder, numFiles) 
{
	var diem=topD.document.getElementById('delete_if_empty');
	if (diem.checked)
		if(numFiles > 0) {
			alert("There are "+numFiles+" files/folders in \""+folder+"\".\n\nPlease delete all files/folder in \""+folder+"\" first.");
			return false;
		}

	if(confirm("Delete folder \""+folder+"\"?")) 
		return true;

	return false;
}



function linkaj(link) {
 	window.location.href="images.php"+link+"&subfolder=<? echo $subfolder; ?>";
}



//-->
</script>
</head>
<body onload="topD.updateDir('<? echo $newPath; ?>');topD.layerVis('loading','hidden');<? echo $onLoad; ?>" bgcolor="#FFFFFF">

<?
//var_dump($_GET);
//echo $dirParam.':'.$upDirPath;
//echo '<br>';
$d = @dir($BASE_DIR.$IMG_ROOT);

if($d) 
{
	//var_dump($d);
	$images = array();
	$folders = array();
	while (false !== ($entry = $d->read())) 
	{
		$img_file = $IMG_ROOT.$entry; 

		if(is_file($BASE_DIR.$img_file) && substr($entry,0,1) != '.') 
		{	
			$image_info = @getimagesize($BASE_DIR.$img_file);
			if(is_array($image_info)) 
			{
				$file_details['file'] = $img_file;
				$file_details['img_info'] = $image_info;
				$file_details['size'] = filesize($BASE_DIR.$img_file);
				$file_details['thumb']=0;
				$file_details['big_info']=$image_info;
						//--------------------------check if is thumb
			
				if (substr_count ( $BASE_DIR.$img_file, "/thumbs/")>0) {
					$big=str_replace("/thumbs/","/",$BASE_DIR.$img_file);
					if (is_file($big))  {
						$image_info_big= @getimagesize($big);
						if(is_array($image_info_big)) {
							$file_details['thumb']=1;
							$file_details['big_info']=$image_info_big;
						}
					}
				}
				$images[$entry] = $file_details;				
			} else if (strpos(strtolower($img_file),'.zip')>1) {
				$tarr=array(0,0,'ZIP','');
				$file_details['file'] = $img_file;
				$file_details['img_info'] = $tarr;
				$file_details['size'] = filesize($BASE_DIR.$img_file);
				$file_details['thumb']=0;
				$file_details['big_info']=$tarr;
				$images[$entry] = $file_details;
			}
			
	
		}
		else if(is_dir($BASE_DIR.$img_file) && substr($entry,0,1) != '.') 
		{
			$folders[$entry] = $img_file;
			//show_dir($img_file, $entry);	
		}
	}
	$d->close();	
	
	if(count($images) > 0 || count($folders) > 0) 
	{	
		//now sort the folders and images by name.
		ksort($images);
		ksort($folders);

		draw_table_header();

		for($i=0; $i<count($folders); $i++) 
		{
			$folder_name = key($folders);		
			show_dir($folders[$folder_name], $folder_name);
			next($folders);
		}
		for($i=0; $i<count($images); $i++) 
		{
			$image_name = key($images);
			show_image($images[$image_name]['file'], $image_name, $images[$image_name]['img_info'], $images[$image_name]['size'], $images[$image_name]['thumb'], $images[$image_name]['big_info']);
			next($images);
		}
		draw_table_footer();
	}
	else
	{
		draw_no_results();
	}
}
else
{
	draw_no_dir();
}

?><script><? echo $onLoad; ?></script>
</body>
</html>