<?

class simContent extends simDBTable {
	var $id=null;
	var $parentID=null;
	var $groupID=null;
	var $listID=null;
	var $type=null;
	var $galleryID=null;
	var $image=null;
	var $ordering=null;
	var $published=null;
	var $frontpage=null;
	var $title=null;
	var $keywords=null;
	var $description=null;
	var $menu=null;
	var $intro=null;
	var $content=null;
	
	function simContent( &$db ) {
		$this->simDBTable( 'content', 'id', $db );
		$this->setAsHtml('content,intro');		
	}
	function clearCode($src,$repl='') {
		$this->content=str_replace($src,$repl,$this->content);
	}
}






?>