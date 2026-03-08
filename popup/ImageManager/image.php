<?
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

$filename="";
if(isset($_GET['imagepath'])) {
	$imagepath = $_GET['imagepath'];
	$filename=substr($imagepath,1+strrpos($imagepath,"/"));
}
else $imagepath="noimage.png";


function show_image($img) 
{

?>

          <img src="<? echo $img; ?>" border="0" onLoad="hideMessage();">

<?
}


?>
<html>
<head>
<title>Image Browser</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<script language="JavaScript" type="text/JavaScript">
function hideMessage(){
	var topD = window.top;
	topD.layerVis('loading','hidden');
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: x-small;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body  bgcolor="#FFFFFF" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr>
<td height="20" width="100%" align="center" bgcolor="#EEEEEE"><span class="style1"><? echo $filename; ?></span></td>
</tr>
<tr><td valign="middle" align="center" width="100%" height="100%">
<?

			show_image($imagepath);


?>
</td></tr></table>

</body>
</html>