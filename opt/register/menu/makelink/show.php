<?


switch ($row->mact) {
	case 'logout': $row->link="opt=register&amp;act=".$row->mact; $row->dbindex="0"; break;
	default:   $row->link="opt=register";
}


?>