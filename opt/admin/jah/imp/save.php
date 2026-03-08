<?
DEFINE("_DEL",true);
$skipArray=array('Thumbs.db');

function initializeImportFile(&$arr,$path,$entry,$ix) {
			global $database,$simConfig_absolute_path,$skipArray,$simConfig_YEAR;
			if(!in_array($entry,$skipArray)) {
				$obj=new stdClass;
				$obj->id=$ix;
				$obj->file=$entry;
				if(substr_count(strtolower($entry),' za '.$simConfig_YEAR)>0) $obj->type='agencijsko_sudjelovanje';
				else if(substr_count(strtolower($entry),'promjen')>0) $obj->type='clan_update';
				else if(substr_count(strtolower($entry),'airplay-digi-poveznica')>0) $obj->type='airplay_poveznica';
				else if(substr_count(strtolower($entry),'airplay-correct')>0) $obj->type='airplay_correct';
				else if(substr_count(strtolower($entry),'airplay')>0) $obj->type='airplay';
				else if(substr_count(strtolower($entry),'album_snimk')>0) $obj->type='album_snimka';
				else if(substr_count(strtolower($entry),'apopravak')>0) $obj->type='apopravak';
				else if(substr_count(strtolower($entry),'snimk')>0) $obj->type='snimka';
				else if(substr_count(strtolower($entry),'album')>0) $obj->type='album';
				else if(substr_count(strtolower($entry),'clan')>0) $obj->type='clan';
				else if(substr_count(strtolower($entry),'izvodjaci')>0) $obj->type='sudjelovanje';
				else if(substr_count(strtolower($entry),'sudjelovanj')>0) $obj->type='sudjelovanje';
				else if(substr_count(strtolower($entry),'postaje')>0) $obj->type='postaje';
				else if(substr_count(strtolower($entry),'dnevnik')>0) $obj->type='dnevnik';
				else $obj->type='unknown';
				$arr[]=$obj;
				return 1;
			} else return 0;
}




$dcode=date("Y-m-d_His");
	
$pth="jq/upload/server/php/files";
$uploadpath=$simConfig_absolute_path."/$pth";
$importpath=$simConfig_absolute_path."/imp/".$dcode;

$type=trim(simGetParam($_POST,'type',''));
$archiveurl=trim(simGetParam($_POST,'url',''));
$archivename=getSysName(trim(simGetParam($_POST,'name','')));



$filename=str_replace($simConfig_live_site."/$pth/",'',$archiveurl);
mkdir($importpath);
rename("$uploadpath/$filename","$importpath/$archivename");

$file=pathinfo("$importpath/$archivename");
$cnt=0;	
$arr=array();


switch($file['extension']) {
		case 'zip':
	
			$zippath=pathinfo($importpath.'/'.$archivename);
			if (!is_dir($importpath.'/'.$zippath["filename"])) {
				require_once ($simConfig_absolute_path."/inc/lib/pclzip.lib.php");
				$zipfile = new PclZip($importpath.'/'.$archivename);
				$ret = $zipfile->extract(PCLZIP_OPT_PATH,$importpath,PCLZIP_CB_PRE_EXTRACT,'prepareZip');
				foreach($ret as $f) if (!$f['folder']){
					$cnt+=initializeImportFile($arr,$importpath,$f['stored_filename'],$cnt);
				}
				delFile($importpath.'/'.$archivename);
				
			}
			
			break;
		case 'rar':
			$rarpath=pathinfo($importpath.'/'.$archivename);
			if (!is_dir($importpath.'/'.$rarpath["filename"])) {
				$rar_file = rar_open($importpath.'/'.$archivename);
				$entries = rar_list($rar_file);
				foreach ($entries as $e) {
					$path_parts = pathinfo($e->getName());
					$e->extract('',$importpath.prepareRar('',$e->getName()));
					if(!$e->isDirectory()) $cnt+=initializeImportFile($arr,$importpath,$e->getName(),$cnt);
				}
				
				rar_close($rar_file);
				delFile($importpath.'/'.$archivename);
				
				
			}
	
			break;
		default:
			$cnt+=initializeImportFile($arr,$importpath,$archivename,$cnt);
			
			
		
	}
	
	
	
	$res->javascript("hideStandardPopup('editbox');");
	
	$tmpl->readTemplatesFromInput( "opt/admin/jah/uploadprocess.html");
	$tmpl->addVar("opt_a_file", 'folder',$dcode);
	$tmpl->addObject("opt_a_file", $arr,'row_', false);
	$cont= $tmpl->getParsedTemplate("opt_admin");
	
	$res->change('popuptitle','Import datoteka');
	$res->change('ed_content',$cont);
	$res->javascript("showEditPopup('popupdescription');");
	
	

	
	
	

?>