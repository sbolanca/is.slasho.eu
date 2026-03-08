<?
$opt=trim($_GET['opt']);
if (file_exists("opt/$opt/js/cm_actions.js") && file_exists("opt/$opt/lang/hr.js")) {
	$langfull=file("opt/$opt/lang/hr.js");
	$lang=array();
	foreach($langfull as $red) if (substr_count($red,':')>0) {
		$arr=split(':',$red);
		$lang[trim($arr[0])]=trim($arr[1]);
	}
	$file=file_get_contents("opt/$opt/js/cm_actions.js");
	$arrs=array_keys($lang);
	rsort ($arrs,SORT_STRING);
	reset ($arrs);

	foreach($arrs as $k) $file=str_replace($k,$lang[$k],$file);
	$f=fopen("opt/$opt/js/cm_actions.js","w");
	fputs($f,$file);
	fclose($f);
	echo "OK";
} else echo "Nema nista";


?>