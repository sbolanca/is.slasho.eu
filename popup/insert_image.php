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
function dirs($dir,$abs_path) 
{
	$d = dir($dir);
		$dirs = array();
		while (false !== ($entry = $d->read())) {
			if(is_dir($dir.'/'.$entry) && substr($entry,0,1) != '.') 
			{
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
			echo "<option value=\"$current_dir\">$current_dir</option>\n";
			dirs($dirs[$name]['path'],$current_dir);
			next($dirs);
		}
}
$funcNum = isset($_GET['CKEditorFuncNum'])?$_GET['CKEditorFuncNum']:0; 
$CKEditor = isset($_GET['CKEditor'])?$_GET['CKEditor']:''; 

	

	include 'ImageManager/config.inc.php';
	$bigimagesize=$simConfig_defimagesize;

		$mainWidth=$IMGCONF[$model][0];
		$mainHeight=$IMGCONF[$model][1];
		$thModels=explode("|",$IMGCONF[$model][2]);
		$thModel=array();
		foreach($thModels as $thm) {
			$thModel[$thm]=$IMGTHCONF[$thm];
		}
		
		
		$thumbFolder=$IMGTHCONF[$thModels[0]][0];
		$thumbWidth=$IMGTHCONF[$thModels[0]][1];
		$thumbHeight=$IMGTHCONF[$thModels[0]][2];
		$resizeThumbType=$IMGTHCONF[$thModels[0]][3];

		
	
	
	$gotoscript='';
	if (isset($_GET['gp']) && trim($_GET['gp'])) {
			$gp=str_replace("//","/",trim($_GET['gp']));	
			$gp=trim($gp,"/");
			if ($thumbFolder) $gp=str_replace($thumbFolder."/","",$gp);
			if ($subfolder && (substr($gp,0,strlen($subfolder))==$subfolder)) $gp=trim(substr($gp,strlen($subfolder)),"/");
			$dgp=substr($gp,0,strrpos($gp,"/"));
			$fgp=trim(substr($gp,strrpos($gp,"/")),"/");
			if ($dgp) $gotoscript="updateDir('/".$dgp."');";

	} else $gp='';
	
	
	
	
	$no_dir = false;
	if(!is_dir($BASE_DIR.$BASE_ROOT)) {
		$no_dir = true;
		//echo $BASE_DIR.$BASE_ROOT."<BR>";
	}
	

	{

?>
<html >
<head>
<title>Insert/edit image</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<link href="ImageManager/css.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
window.resizeTo(800, 680);
var preview_window = null;
var def_subfolder='<? echo $subfolder; ?>';
var model='<? echo $model; ?>';

var funcNum='<? echo $funcNum; ?>';
var CKE='<? echo $CKEditor; ?>';
var gp='';
var dgp='';
if (CKE) {
	var CKEDITOR = window.opener.CKEDITOR;
	var dialog = CKEDITOR.dialog.getCurrent();
	if (dialog.getName() == 'image')   {
		currentURL=dialog.getValueOf( 'info', 'txtUrl');
		gp=currentURL.replace("/files/Image/","");
		dgp=gp.substring(0,gp.lastIndexOf("/"));
	}
} 
function goCKEDir() {
	if (dgp) updateDir("/"+dgp);
	if (gp) {
		document.getElementById('form1').action+=gp;
		document.getElementById('form2').action+=gp;
	}
}
</script>
<script language="JavaScript" src="ImageManager/script.js" type="text/JavaScript"></script>
<body onload="Init();goCKEDir();<?echo $gotoscript;?>;actionx('updatedir')">
<form action="ImageManager/images.php?subfolder=<? echo $subfolder; ?>&gp=<? echo $gp; ?>" name="form1" id="form1" method="post" target="imgManager" enctype="multipart/form-data">
<input name="model" type="hidden" id="model" value="<? echo $model; ?>"/>
<input name="url" id="url" type="hidden"/>
<input name="act" id="act" type="hidden"/>
<input name="parentDir" id="parentDir" type="hidden"/>
<div id="loading" style="position:absolute; left:200px; top:130px; width:184px; height:48px; z-index:1" class="statusLayer">
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><div align="center"><span id="loadingStatus" class="statusText">Loading images</span><img src="ImageManager/dots.gif" width="22" height="12"></div></td>
    </tr>
  </table>
</div>

<fieldset>
<div id="boxes">
		<legend>Image Browser</legend>
		<div id="filebox">
			<div id="folders">
				<table>
					<tr>
						<td>Directory:</td>
						<td><select name="dirPath" id="dirPath" style="width:200px" onChange="actionx('updatedir')">
							  <option value="/">/</option>
							  <?	if($no_dir == false) {	dirs($BASE_DIR.$BASE_ROOT,'');	}						?>
							</select>
						</td>
						<td class="buttonOut" onMouseOver="pviiClassNew(this,'buttonHover')" onMouseOut="pviiClassNew(this,'buttonOut')">
				 			 <a href="#" onClick="goUpDir();"><img src="ImageManager/btnFolderUp.gif" width="15" height="15" border="0" alt="Up"></a>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="files">
				 <iframe src="" name="imgManager" id="imgManager"   marginwidth="0" marginheight="0" align="top" scrolling="auto" frameborder="0" hspace="0" vspace="0" background="white"></iframe>
			</div>
			
		</div>
		
		
			<div id="imagebox">
				 <div id="cropbox" onMouseDown="CStartDrag(event)" onMouseUp="CStopDrag(event)" onMouseMove="CDrag(event)" onMouseOut="CStopDrag(event,true)" onMouseOver="if (cropDragging) CStartDrag(event)"></div>
				 <div id="imgPreview" ></div>
		</div>
		<div class="cleaner"></div>	
</div>
</fieldset>
<div id="commands">
	<table>
		<tr>
			<td valign="top">
				
				<fieldset>
				  <legend>File</legend>
					<table>
						<tr> 
							<td nowrap>Name:</td>
							<td nowrap width="200"><span id="filename" style="font-weight:bold"></span></td>
						</tr>
						<tr> 
							<td nowrap>Size:</td>
							<td width="200"><span id="f_width" style="font-weight:bold">---</span> x <span id="f_height" style="font-weight:bold">---</span> px</td>
						</tr>
					</table>
				</fieldset>
				<fieldset>
				  <legend>Upload</legend>
				  	<table>
						<tr>
							<td><input type="file" style="width:192px" name="upload" id="upload" /> </td>
							<td><input type="button" style="width:60px" value="Upload" onClick="actionx('upload','upload');" /></td>
							<td nowrap align="right" width="70">autounzip:</td>
							<td><input name="unzip" type="checkbox" id="unzip" value="yes" /></td>
						</tr>
					</table>
					<table>	
						<tr>
							<td nowrap align="right">make thumbnail:</td>
							<td><input name="makeThumb" type="checkbox" id="makeThumb" value="make" checked /></td>

							<td nowrap align="right" width="100">resize to (max):</td>
							<td><input type="text" name="resizevalue" id="resizevalue" style="width:30px" value="<? echo $mainWidth ?>"/>width</td>
							<td><input type="text" name="resizevalue2" id="resizevalue2" style="width:30px" value="<? echo $mainHeight ?>"/>height</td>
							<td><input name="resize" type="checkbox" id="resize" value="yes" checked /></td>
							
						 </tr>
					</table>
				</fieldset>
				<fieldset>
				  <legend>Subfolder</legend>
				  <table>
						<tr>
							<td><input name="subfolder_name" id="subfolder_name" type="text" style="width:120px" size="10"/></td>
							<td style="width:60px"><input name="subfolder" type="button" id="btn1" style="width:60px" onClick="actionx('createsubfolder');" value="Create" /></td>
							<td nowrap align="right">Briši samo ako je prazan:</td>
							<td style="width:1em"><input name="delete_if_empty" type="checkbox" id="delete_if_empty" value="del_empty" checked/></td>
						</tr>
				</table>
				</fieldset>	
				
			</td>
			<td valign="top">
				<div>
					<table>
						<tr>
							<td><button type="button" name="ok" onclick="return onOK('<? echo $model; ?>');">OK</button></td>
							<td><button type="button" name="refresh" onclick="actionx('updatedir');">Refresh</button></td>
							<td><input type="reset" name="reset" value="Reset" style="width:70px;" onClick="actionx('root','load')" /></td>
							<td><button type="button" name="cancel" onclick="window.close();">Cancel</button></td>
						</tr>
					</table>
				</div>
				<table><tr><td>
					<fieldset>
						 <legend>Thumbnailing &amp; crop settings</legend>
						 <input id="offsetX" name="offsetX" type="hidden" value="-1"/>
						 <input id="offsetY" name="offsetY" type="hidden" value="-1"/>
						<table>
							<tr>
								<td>Model:</td>
								<td><select id="dms"  onChange="doModel()" style="background-color:#ffeeee">
					<? foreach ($thModel as $k=>$arr) 
						echo '<option value="'.$k.'-'.implode("-",$arr).'">'.$arr[4].'</option>';
					?>
							</select>			</td>
							</tr>
							<tr>
								<td>Folder:</td>
								<td><input name="thumbFolder" id="thumbFolder" value="<? echo $thumbFolder; ?>" size="10" /></td>
							</tr>
							<tr>
								<td>Width:</td>
								<td><input name="thumbWidth" id="thumbWidth" value="<? echo $thumbWidth; ?>" size="4" maxlength="4" /></td>
							</tr>
							<tr>
								<td>Height:</td>
								<td><input name="thumbHeight" id="thumbHeight" value="<? echo $thumbHeight; ?>" size="4" maxlength="4" /></td>
							</tr>
							<tr>
								<td>JPEG quality:</td>
								<td> <input name="thumbQuality" id="thumbQuality" value="90" size="4" maxlength="3" /></td>
							</tr>	
							<tr>
								<td>Resize option:</td>
								<td>
									<select name="resizeOpt" id="resizeOpt">
											<option value="maxvalue" <?=$resizeThumbType=='maxvalue'?'selected':''?>>resize to max value</option>
											<option value="width" <?=$resizeThumbType=='width'?'selected':''?>>resize to width</option>
											<option value="height" <?=$resizeThumbType=='height'?'selected':''?>>resize to height</option>
											<option value="crop" <?=$resizeThumbType=='crop'?'selected':''?>>crop</option>
									</select>
								</td>
	
							</tr>		
						</table>
						
					</fieldset>
					
					</td>
					<td valign="top">
					<fieldset id="goThumb" style="visibility:hidden; z-index:5">
						<legend>Image:</legend>
							<table>
								<tr><td><input type="button" style="width:7em" value="Crop" onClick="doCrop()" /></td></tr>
								<tr><td><input type="button" style="width:7em" value="Thumbnail" onClick="actionx('thumb','thumb');showImage()" /></td></tr>
								<tr><td><input type="button" style="width:7em" value="Rotate 90&deg;" onClick="actionx('rot90','rot90'); " /></td></tr>
								<tr><td><input type="button" style="width:7em" value="Rotate -90&deg;" onClick="actionx('rot-90','rot-90');" /></td></tr>
							</table>
					</fieldset>	
					</td></tr></table>
			</td>
		</tr>
	</table>
</div>
</form>  

<form action="ImageManager/images.php?subfolder=<? echo $subfolder; ?>&gp=<? echo $gp; ?>" name="form2" id="form2" method="post" target="imgManager">
<input name="url" type="hidden" value=""/>
<input name="act" type="hidden" value=""/>
<input name="model" type="hidden" value=""/>
<input name="resizeOpt" type="hidden" value=""/>
<input name="thumbQuality" type="hidden" value=""/>
<input name="thumbWidth" type="hidden" value=""/>
<input name="thumbHeight" type="hidden" value=""/>
<input name="offsetX" type="hidden" value=""/>
<input name="offsetY" type="hidden" value=""/>
<input name="thumbFolder" type="hidden" value=""/>
<input name="delete_if_empty" type="hidden" value=""/>
<input name="subfolder_name" type="hidden" value=""/>
<input name="resize" type="hidden" value=""/>
<input name="resizevalue" type="hidden" value=""/>
<input name="resizevalue2" type="hidden" value=""/>
<input name="makeThumb" type="hidden" value=""/>
<input name="dirPath" type="hidden" value=""/>
</form>
</body>
</html>
<?php
}
?>