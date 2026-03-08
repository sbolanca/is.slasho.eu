<?

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


	$subfolder=$_GET['subfolder'];
	
	include 'MediaBrowser/config.inc.php';
	$no_dir = false;
	if(!is_dir($BASE_DIR.$BASE_ROOT)) {
		$no_dir = true;
		//echo $BASE_DIR.$BASE_ROOT."<BR>";
	}
$AEA=array();	
foreach ($allowedExt as $a) $AEA[]="'".$a."'";


	{

?>
<html >
<head>
<title>Media browser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<link href="MediaBrowser/css.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
window.resizeTo(760, 460);
var preview_window = null;
var def_subfolder='<? echo $subfolder; ?>';
//var allowedExt=[<? echo implode(',',$AEA); ?>];


</script>
<script language="JavaScript" src="MediaBrowser/script.js" type="text/JavaScript"></script>


<body onload="Init();">
<form action="MediaBrowser/images.php?subfolder=<? echo $subfolder; ?>" name="form1" id="form1" method="post" target="imgManager" enctype="multipart/form-data">
<input name="url" id="url" type="hidden"/>
<input name="act" id="act" type="hidden"/>
<input name="parentDir" id="parentDir" type="hidden"/>
<div id="loading" style="position:absolute; left:200px; top:130px; width:184px; height:48px; z-index:1" class="statusLayer">
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><div align="center"><span id="loadingStatus" class="statusText">Učitavanje</span><img src="MediaBrowser/dots.gif" width="22" height="12"></div></td>
    </tr>
  </table>
</div>

<fieldset>
<div id="boxes">
		<legend>Media Browser</legend>
		<div id="filebox">
			<div id="folders">
				<table>
					<tr>
						<td>Mapa:</td>
						<td><select name="dirPath" id="dirPath" style="width:200px" onChange="actionx('updatedir')">
							  <option value="/">/</option>
							  <?	if($no_dir == false) {	dirs($BASE_DIR.$BASE_ROOT,'');	}						?>
							</select>
						</td>
						<td class="buttonOut" onMouseOver="pviiClassNew(this,'buttonHover')" onMouseOut="pviiClassNew(this,'buttonOut')">
				 			 <a href="#" onClick="goUpDir();"><img src="MediaBrowser/btnFolderUp.gif" width="15" height="15" border="0" alt="Up"></a>
						</td>
					</tr>
				</table>
			</div>
			<div id="files">
				 <iframe src="MediaBrowser/images.php?subfolder=<? echo $subfolder; ?>" name="imgManager" id="imgManager"  marginwidth="0" marginheight="0" align="top" scrolling="auto" frameborder="0" hspace="0" vspace="0" background="white"></iframe>
			</div>
		</div>
		<div id="imagebox">
				<fieldset>
				  <legend>Datoteka</legend>
					<table>
						<tr> 
							<td nowrap>Naziv:</td>
							<td nowrap width="200"><span id="filename" style="font-weight:bold"></span></td>
						</tr>
						<tr> 
							<td nowrap>Path:</td>
							<td width="200"><input  type="text" name="longfilename" id="longfilename" style="width:350px" value=""/></td>
						</tr>
						
					</table>
				</fieldset>
				<fieldset>
				  <legend>Upload</legend>
				  	<table>
						<tr>
							<td nowrap align="right">Media:</td>
							<td><input type="file" name="upload" id="upload" > </td>
							<td><input type="button" style="width:5em" value="Upload" onClick="actionx('upload','upload');" /></td>
						</tr>
					</table>
					
				</fieldset>	
				<fieldset>
				  <legend>Podmapa</legend>
				  <table>
						<tr>
							<td><input name="subfolder_name" id="subfolder_name" type="text" style="width:8em" size="10"/></td>
							<td style="width:8em"><input name="subfolder" type="button" id="btn1" style="width:4em" onClick="actionx('createsubfolder');" value="Kreiraj" /></td>
							<td nowrap align="right">Briši samo ako je prazan:</td>
							<td style="width:1em"><input name="delete_if_empty" type="checkbox" id="delete_if_empty" value="del_empty" checked/></td>
						</tr>
				</table>
				</fieldset>	
				<div id="btnbox">
					<table>
						<tr>
							<td><button type="button" name="ok" onclick="return onOK();">OK</button></td>
							<td><button type="button" name="refresh" onclick="actionx('updatedir');">Refresh</button></td>
							<td><input type="reset" name="reset" value="Reset" style="width:70px;" onClick="actionx('root','load')" /></td>
							<td><button type="button" name="cancel" onclick="window.close();">Odustani</button></td>
						</tr>
					</table>
				</div>	
		</div>
		<div class="cleaner"></div>	
</div>
</fieldset>

</form>  

<form action="MediaBrowser/images.php?subfolder=<? echo $subfolder; ?>" name="form2" id="form2" method="post" target="imgManager">
<input name="url" type="hidden" value=""/>
<input name="act" type="hidden" value=""/>
<input name="delete_if_empty" type="hidden" value=""/>
<input name="subfolder_name" type="hidden" value=""/>
<input name="dirPath" type="hidden" value=""/>

</form>
</body>
</html>
<?php
}
?>