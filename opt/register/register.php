<?

global $lang;

if ($act=='logout') {
			$LOG->saveIlogNOW(1,"Logout",$_SESSION['MM_name'],'',-1,'login');
	header("Location: logout.php"); 
	die();
}

     include_once('opt/register/lang/hr.php');
if ($act=="show") $act="form"; 



$task=$act;

if ($act=="new") $tmplfile="form"; 

?>