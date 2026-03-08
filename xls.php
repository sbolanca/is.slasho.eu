<?php
session_start();

$trTag='TR';
$tdTag='TD';
$bodyTag='TBODY';

include_once( 'inc/version.php' );
include_once( 'globals.php' );
require_once( 'configuration.php' );
require_once( 'opt/settings/settings.config.php' );
error_reporting ($simConfig_error_reporting);
require_once( 'inc/common.php' );
require_once( 'inc/include.php' );
require_once( 'inc/ajxdatabase.php' );

$database = new database( $simConfig_host, $simConfig_user, $simConfig_password, $simConfig_db );


$myID=intval($_SESSION['MM_id']);
$isJah=true;
$isSuper= intval($_SESSION['MM_super']);
$isAdmin= intval($_SESSION['MM_admin']);
$arhiva=trim(simGetParam($_SESSION,'pos_arhiva',''));

$showTableColors=intval(simGetParam($_REQUEST,'showTableColors',0));
$tbl=trim(simGetParam($_REQUEST,'tbl',''));
$opt=trim(simGetParam($_REQUEST,'opt',''));
$act=str_replace("-","/",trim(simGetParam($_REQUEST,'act','')));
$id=intval(simGetParam($_REQUEST,'id',0));
$posStart=intval(simGetParam($_REQUEST,'posStart',0));
$count=intval(simGetParam($_REQUEST,'count',100));
$total_count=intval(simGetParam($_REQUEST,'total_count',0));

$tblStylesTransform=array(
'white'=>"color:#fff",
'yellow'=>"color:#ff5",
'blue'=>"color:#0000cc",
'red'=>"color:#cc0000",
'green'=>"color:#00aa00",
'brown'=>"color:#777700",
'grey'=>"color:#999999",
'ljub'=>"color:#a800c1",
'darkcyan'=>"color:#008888",
'gold'=>"color:#A8760B",
'deeppink'=>"color:#FF1493",
'indigo'=>"color:#4B0082",
'tomato'=>"color:#FF6347",
'teal'=>"color:#008080",
'seagreen'=>"color:#2E8B57",
'redfull'=>"color:#ff0000",
'my'=>"background-color:#DDFFDD",
'mysukob'=>"background-color:#DDDDFF",
'sukob'=>"background-color:#FFDDDD",
'own'=>"background-color:#FFFFDD",
'pink'=>"background-color:#FFDDFF",
'cyan'=>"background-color:#DDF4FF",
'sunset'=>"background-color:#EEDDFF"
);

$xls_file_title=trim(simGetParam($_SESSION,$tbl."TITLE",$opt."-".$act));
$xls_file_description=trim(simGetParam($_SESSION,$tbl."SO",''));


header("Content-Type: application/vnd.ms-excel;charset=utf-8");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=".clearFilename($xls_file_title).".".date("YmdHis").".xls");


?><html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$xls_file_title?></title>
<style>
table td,table th{vertical-align:middle}
table td {
	border-bottom:0.1pt solid #dddddd;
	border-right:0.1pt solid #dddddd;
}
table th {
	background-color:#d4d0c8;
	font-weight:normal;
	border-right:0.1pt solid #eeeeee;
	border-top:0.1pt solid #ffffff;
	border-bottom:0.1pt solid #aaaaaa;
}
</style>
</head><body><table><thead><tr><?
$tbl_fields_Arr=explode(",",$_SESSION['tbl_'.$tbl.'_fields']);

if($xls_file_description) echo '<td style="color:#eeeeee" bgcolor="333333" align="left" colspan="'.count($tbl_fields_Arr).'">'.$xls_file_description.'</td></tr><tr>';

$tbl_Header_Arr=getHeaderArrayFromSessionField('tbl_'.$tbl);
foreach($tbl_fields_Arr as $tbl_field) {
	echo "<th>".$tbl_Header_Arr[$tbl_field]."</th>";
	
}
echo "</tr></thead>";

if (file_exists( 'opt/'.$opt.'/ajx/'.$act.'.php' ))
	require_once(  'opt/'.$opt.'/ajx/'.$act.'.php' );
else {
		$msg="Ajax akcijski fajl\n'".'opt/'.$opt.'/ajx/'.$act.'.php'."'\nne postoji!";
}
echo("</table></body></html>");
?>
