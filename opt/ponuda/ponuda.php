<?

include_once("opt/ponuda/ponuda.class.php");

$task=$act;
$pagetype=$act;
switch ($act) {

		case 'analiza':case 'show':case 'recyclebin':case 'marker':case 'vlainsstvo':case 'free':case 'instrument':case 'zahtjev':case 'folder': case 'list': case 'sukob': case 'actual': case 'izvodjenje': case 'mylist': case 'nocommerc': case 'listi': case 'search':  $task='list';  break;
}

$mainFrame->allowCM=false;
 


?>