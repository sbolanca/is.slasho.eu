<?
require_once('opt/stavka/folder.class.php');


$folderID=trim(simGetParam($_REQUEST,'folderID',0));

$fld=new simStaFolder($database);
$fld->load($folderID);

$SQL=trim($_SESSION['stavkaRawSQL']);

if($folderID) {
	$cntBefore=intval($database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID"));
	$database->execQuery("INSERT IGNORE INTO sta_folder_stavka (folderID,stavkaID) "
	."\n SELECT '$folderID' as fid,s.id"
		."\n $SQL ");
		
	$cntAfter=intval($database->getResult("SELECT COUNT(stavkaID) FROM sta_folder_stavka WHERE folderID=$folderID"));
	$diff=$cntAfter-$cntBefore;
	if($diff) {
			$msg="U folder je dodano $diff novih zapisa.";
			$LOG->saveIlog(1,"Prebacivanje ispisa stavki u folder",$fld->naziv,"folderID=$folderID\n\nUbačeno $diff novih stavki u folder.",0,false);
	} else $msg='Nije ubačen nijedan novi zapis u folder.';
	$res->javascript("if(confirm('$msg\\n\\nŽelite li se prebaciti na pregled foldera?')) location.href='index.php?opt=stavka&act=folder&folderID=$folderID';");
} else $res->alert("Greška kod odabira foldera!");

?>