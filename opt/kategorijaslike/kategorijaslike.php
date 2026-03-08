<?

include_once("opt/kategorijaslike/kategorijaslike.class.php");

$task=$act;

switch ($act) {

		case 'analiza':case 'show':case 'recyclebin':case 'marker':case 'vlainsstvo':case 'free':case 'instrument':case 'zahtjev':case 'folder': case 'list': case 'sukob': case 'actual': case 'izvodjenje': case 'mylist': case 'nocommerc': case 'listi': case 'search': $pagetype=$act; $task='list';  break;
}

$mainFrame->allowCM=false;
 


?>