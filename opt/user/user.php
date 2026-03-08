<?



$task=$act;

if (!$mainFrame->isAdmin) simRedirect("index.php?opt=register&act=logout");


if ($act=='search') {
	$usr_name=trim(simGetParam($_POST,'usr_name',''));
	$_SESSION['usr_name'] =$usr_name; 
}

switch ($act) {

	case 'specijalniupit':case 'list': case 'show':  case 'search':  $task='list';  break;
}
$pagetype=$act;

$mainFrame->setTitle("Članovi");

?>