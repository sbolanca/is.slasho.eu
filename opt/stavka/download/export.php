<?
function readfile_chunked($filename, $retbytes = TRUE) {
	 $buffer = '';
    $cnt =0;
    $handle = fopen($filename, 'rb');
    if ($handle === false) {
      return false;
    }
    while (!feof($handle)) {
      $buffer = fread($handle, CHUNK_SIZE);
      echo $buffer;
      ob_flush();
      flush();
      if ($retbytes) {
        $cnt += strlen($buffer);
      }
    }
    $status = fclose($handle);
    if ($retbytes && $status) {
      return $cnt; // return num. bytes delivered like readfile() does.
    }
    return $status;
  }


$ids=$_SESSION['sta_clipboard'];

$q="SELECT x.* "
." FROM stavka AS x"

.($ids?" WHERE x.id IN ($ids)":" WHERE 1=0")
." ORDER BY x.id";	
		
//{$fp=fopen("sql.txt","w");fputs($fp,$q);fclose($fp); }


$database->setQuery($q);
$rows=$database->loadObjectList();
//$ispis=iconv('UTF-8',"ISO-8859-2","HUZIP ŠIFRA|NAZIV SNIMKE|NAZIV IZVOÐAÈA SNIMKE|AUTORI|TRAJANJE|GODINA|DXS|NAZIV IZVOÐAÈA NA SNIMCI|ŠIFRA VRSTE SUDJELOVANJA|ŠIFRE INSTRUMENATA\n");
//$ispis="HUZIP ŠIFRA|NAZIV SNIMKE|NAZIV IZVOÐAÈA SNIMKE|AUTORI|TRAJANJE|GODINA|DXS|NAZIV IZVOÐAÈA NA SNIMCI|ŠIFRA VRSTE SUDJELOVANJA|ŠIFRE INSTRUMENATA\r\n";
$ispis="";
foreach ($rows as $row) {
	
	//$ispis.=iconv('UTF-8',"windows-1250","$row->id|$row->code|$row->naziv|$row->mjera|".makeHRFloat($row->iznos,' kn')."\r\n");
	$ispis.="$row->id|$row->code|$row->naziv|$row->mjera|".makeHRFloat($row->iznos,' kn')."\r\n";
	//$ispis.="$row->huzip|$row->naziv|$row->izvodjac|$row->autori|$row->trajanje|$row->godina|$row->dxs|$row->inaziv|$row->vrstaID|$row->uloge\r\n";
}
$filename="Stavke_export__".date("d.m.Y_H-i-s");
$path=$simConfig_absolute_path."/files/File/export/stavka";
$fp=fopen("$path/$filename.TXT","w");
fputs($fp,$ispis);
fclose($fp);
require_once ($simConfig_absolute_path."/inc/lib/pclzip.lib.php");
$zipfile = new PclZip("$path/$filename.zip");
$zipfile->create("$path/$filename.TXT",PCLZIP_OPT_REMOVE_ALL_PATH);

//$res->alert("Izvještaj je generiran. Downloadat æe se export datoteka datoteka $filename");
//$res->javascript("window.location.assign('/files/File/export/promjena/$filename')");
define('CHUNK_SIZE', 1024*1024); 
$mimetype = 'mime/type';
			



header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$filename.zip");
header("Content-length: " . filesize("$path/$filename.zip"));
header("Pragma: no-cache"); 
header("Expires: 0"); 
ob_clean();
flush();
//readfile("$path/$filename.zip");
readfile_chunked("$path/$filename.zip");
unlink("$path/$filename.TXT");
unlink("$path/$filename.zip");


?>