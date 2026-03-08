<?

include_once("opt/mail/mail.class.php");

$task=$act;

switch ($act) {

		case 'show':case 'list': case 'search': $pagetype=$act; $task='list';  break;
}

$mainFrame->allowCM=false;
 


?>