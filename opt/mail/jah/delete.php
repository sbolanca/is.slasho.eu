<?

include_once("opt/mail/mail.class.php");
$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->setQuery("SELECT * FROM mail WHERE id IN ($ids)");
$rows=$database->loadObjectList('id');

		clearTable('mail','id',$ids);
	
		$logTitle="Brisanje mail predloška";	
	
	
	$list=array();
	foreach($rows as $ix=>$row) {
		$list[]=$row->title;
		$res->deleteRow("tbl_mail",$ix);
	
	}
	$LOG->saveIlog(1,$logTitle,getMultiTitle($rows,$list[0]),implode("\n",$list),$id,false);


?>