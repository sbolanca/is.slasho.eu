<?




			
		$q="SELECT u.* "
		.(($mainFrame->isAdmin) ? ", ".makeSqlCM(true,"CM_opt_user","u.id").", ".makeSqlTagID("u_","u.id") :"")
		."\n FROM user as u"
		."\n ORDER BY u.username";
		$database->setQuery($q);		
		$rows=$database->loadObjectList();
		for($i=0;$i<count($rows);$i++) {
			$rows[$i]->pair=($i % 2) +1;
			$rows[$i]->rbr=$i +1;
			
		}
		$tmpl->addObject("opt_user_list", $rows, "row_",true); 
if ($mainFrame->isAdmin) {
	 $tmpl->addCMVar( "opt_user_table", "CM_TABLEHEADER", 'CM_opt_user_header',0);
}


?>