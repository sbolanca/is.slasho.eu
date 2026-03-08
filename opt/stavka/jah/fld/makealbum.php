<?

// PROÈISTITI OSTATKE OD DISCO
include_once("opt/album/album.class.php");
include_once("opt/stavka/folder.class.php");	

$folderID=intval(simGetParam($_REQUEST,'folderID',0));
$refresh=intval(simGetParam($_REQUEST,'refresh',0));

	$fld=new simStaFolder($database);
	$fld->load($folderID);


	$database->execQuery("INSERT INTO album (naziv) VALUES (UPPER('".addslashes($fld->naziv)."'))");
	$albID=$database->insertid();
	
	$database->execQuery("INSERT IGNORE INTO album_stavka (albumID,stavkaID) (SELECT '$albID' as albumID,stavkaID FROM sta_folder_stavka WHERE folderID=$folderID)");
	
	$row=new simAlbum($database);
	$row->load($albID);
	$tmpl->readTemplatesFromInput( "opt/album/jah/edit.html");
	
		
	
	
	$tmpl->addObject("opt_album", $row, "row_",true);
	$cont= $tmpl->getParsedTemplate("opt_album");
	
	$row->load($albID);
	if ($pageopt=='album') $res->addRow('tbl_album',$row,'naziv,izvodjac,godina','');

	$res->change('popuptitle',	"NOVI ALBUM IZ FOLDERA '".$fld->naziv."'");
	$res->change('ed_content',	$cont);
	$res->javascript("showEditPopup('popupdescription');");	
	
	$database->setQuery("SELECT CONCAT(s.naziv,' / ',s.izvodjac) as txt FROM sta_folder_stavka AS f LEFT JOIN stavka AS s ON s.id=f.stavkaID WHERE f.folderID=$folderID");
	$listaSnimki=$database->loadResultArray();
	
	$LOG->savelog("Pretvaranje foldera u album",$fld->naziv." [albumID=$albID]", "broj stavki: ".count($listaSnimki)."<br>".implode("<br>",$listaSnimki));

	$res->sound('rattle');

?>