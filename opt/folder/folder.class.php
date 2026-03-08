<?


class simFolder extends simDBTable {
	var $id=null;
	var $naziv=null;
	var $parentID=null;
	var $userID=null;
	var $sharing=null;
	var $visibility=null;

	function simFolder( &$db ) {
		$this->simDBTable( 'folder', 'id', $db );
	}
	
}


?>