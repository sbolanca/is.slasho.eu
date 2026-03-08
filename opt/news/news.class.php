<?

class simNews extends simDBTable {
	var $id=null;
	var $parentID=null;
	var $groupID=null;
	var $start_date=null;
	var $end_date=null;
	var $galleryID=null;
	var $image=null;
	var $type=null;
	var $ordering=null;
	var $ordering_front=null;
	var $frontpage=null;
	var $listing=null;
	var $published=null;
	var $archive=null;
	var $title=null;
	var $keywords=null;
	var $description=null;
	var $menu=null;
	var $intro=null;
	var $content=null;
			
	function simNews( &$db ) {
		$this->simDBTable( 'news', 'id', $db );
		$this->setAsHtml('content,intro');
	}
	function check($isJah=false) {
		simDBTable::check($isJah);
		$this->convertDateToSQL('start_date');
		$this->convertDateToSQL('end_date');
		if (!$this->start_date) $this->start_date=date("Y-m-d H:i:s");
		if (!$this->end_date) $this->end_date=$this->start_date;
	}
	function clearCode($src,$repl='') {
		$this->content=str_replace($src,$repl,$this->content);
	}
}





?>