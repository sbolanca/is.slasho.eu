<?


class simStaFolder extends simFolder {
	
	
	function simStaFolder( &$db ) {
		$this->simDBTable( 'sta_folder', 'id', $db );
	}
	
}


?>