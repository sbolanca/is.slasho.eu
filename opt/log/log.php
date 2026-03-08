<?


//if (!$mainFrame->isSuper) simRedirect("index.php?opt=register&act=logout");

$task=$act;






switch ($act) {

	case 'specijalniupit':case 'list': case 'show':  case 'search': case 'snimka': $pagetype=$act; $task='list';  break;
}

$mainFrame->setTitle("Logovi");


?>