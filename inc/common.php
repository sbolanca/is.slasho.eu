<?
define( "_SIM_NOTRIM", 0x0001 );
define( "_SIM_ALLOWHTML", 0x0002 );
define( "_SIM_CLEARJAH", 0x0004 );
$_local_month=array();$_local_weekday=array();
for($i=1;$i<13;$i++) {
	$_local_month[]=date("F",mktime(0,0,0,$i,1,2006));
}
for($i=0;$i<7;$i++) {
	$_local_weekday[]=date("l",mktime(0,0,0,3,5+$i,2006));
}
function simGetParam( &$arr, $name, $def=null, $mask=0 ) {
	$return = null;
	if (isset( $arr[$name] )) {
		if (is_string( $arr[$name] )) {
			if (!($mask&_SIM_NOTRIM)) {
				$arr[$name] = trim( $arr[$name] );
			}
			if (!($mask&_SIM_ALLOWHTML)) {
				$arr[$name] = strip_tags( $arr[$name] );
			}
			if (!($mask&_SIM_CLEARJAH)) {
				$arr[$name] = clearJah( $arr[$name] );
			}
			if (!get_magic_quotes_gpc()) {
				$arr[$name] = addslashes( $arr[$name] );
			}
			
		}
		return $arr[$name];
	} else {
		return $def;
	}
}

/**
* Strip slashes from strings or arrays of strings
* @param value the input string or array
*/
function simStripslashes(&$value)
{
	$ret = '';
    if (is_string($value)) {
		$ret = stripslashes($value);
	} else {
	    if (is_array($value)) {
	        $ret = array();
	        while (list($key,$val) = each($value)) {
	            $ret[$key] = simStripslashes($val);
	        } // while
	    } else {
	        $ret = $value;
		} // if
	} // if
    return $ret;
} // simStripSlashes

function simRedirect( $url, $msg='' ) {
	if (trim( $msg )) {
	 	if (strpos( $url, '?' )) {
			$url .= '&msg=' . urlencode( $msg );
		} else {
			$url .= '?msg=' . urlencode( $msg );
		}
	}

	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		header( "Location: $url" );
		//header ("Refresh: 0 url=$url");
	}
	exit();
}
function simBindArrayToObject( $array, &$obj, $ignore="", $prefix=NULL, $checkSlashes=true ) {
	if (!is_array( $array ) || !is_object( $obj )) {
		return (false);
	}

	if ($prefix) {
		foreach (get_object_vars($obj) as $k => $v) {
			if (strpos( $ignore, $k) === false) {
				if (isset($array[$prefix . $k ])) {
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? simStripslashes( $array[$k] ) : $array[$k];
				}
			}
		}
	} else {
		foreach (get_object_vars($obj) as $k => $v) {
			if (strpos( $ignore, $k) === false) {
				if (isset($array[$k])) {
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? simStripslashes( $array[$k] ) : $array[$k];
				}
			}
		}
	}

	return true;
}

/**
* Utility function to read the files in a directory
* @param string The file system path
* @param string A filter for the names
* @param boolean Recurse search into sub-directories
* @param boolean True if to prepend the full path to the file name
*/
function simReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
	$arr = array();
	if (!@is_dir( $path )) {
		return $arr;
	}
	$handle = opendir( $path );

	while ($file = readdir($handle)) {
		$dir = simPathName( $path.'/'.$file, false );
		$isDir = is_dir( $dir );
		if (($file <> ".") && ($file <> "..")) {
			if (preg_match( "/$filter/", $file )) {
				if ($fullpath) {
					$arr[] = trim( simPathName( $path.'/'.$file, false ) );
				} else {
					$arr[] = trim( $file );
				}
			}
			if ($recurse && $isDir) {
				$arr2 = simReadDirectory( $dir, $filter, $recurse, $fullpath );
				$arr = array_merge( $arr, $arr2 );
			}
		}
	}
	closedir($handle);
	asort($arr);
	return $arr;
}

function testVal($test,$real,$ret) {
	if (($real!='') && ($test==$real)) return $ret."=\"".$ret."\"";
	else return '';
}
function testArray($test,$arr,$ret) {
	if (in_array($test,$arr)) return $ret."=\"".$ret."\"";
	else return '';
}

function processImg($f) {
	if ($f) $ret="img/$f";
	else $ret="images/blank.jpg";
	return $ret;
}
function createAssocNestedArray($keys) {
	$array=array();
	if (is_array($keys)) $procarr=$keys;
	else if (is_string($keys)) $procarr=explode(",",$keys);
	foreach($procarr as $key) $array[$key]=array();
	return $array;
}
function simMakeNestedArray(&$rows,$keyFieldName) {
	$array=array();
	$curr_key='';
	foreach($rows as $row) {
		if(!($curr_key==$row->$keyFieldName)) { 
			$curr_key=$row->$keyFieldName;
			$array[$curr_key]=array();
		}
		array_push($array[$curr_key],$row);
	}
	return $array;
}
function simMakeSortedNestedArray(&$rows,$keyFieldName='parentID',$top=0,$fld='id') {
	$NestedSorted=array();
	$Nested=simMakeNestedArray($rows,$keyFieldName);
	$NestedSorted[$top]=$Nested[$top];
	return $NestedSorted + sortNestedArrayBySimpleArray($Nested[$top],$Nested,$fld);
	
}
function simFlatNestedArray(&$rows,$top=0,$fld='id') {
	$flat=array();
	foreach($rows[$top] as $el) {
		$flat[]=$el;
		if (array_key_exists($el->$fld,$rows)) $flat=array_merge($flat,simFlatNestedArray($rows,$el->$fld,$fld));
	}
	return $flat;
}
function sortNestedArrayBySimpleArray(&$arr,&$arrmain,$fld='id') {	
	$array=array(); $ret=array();
	foreach ($arr as $el) if (array_key_exists($el->$fld,$arrmain)) $array[$el->$fld]=$arrmain[$el->$fld];
	foreach ($array as $k=>$a) $array=$array + sortNestedArrayBySimpleArray($a,$arrmain,$fld);
	return $array;	
}
function replaceWithSifrarnik(&$array,&$sifrarnik,&$lnk='') {
	for ($i=0;$i<count($array); $i++) if (array_key_exists($array[$i],$sifrarnik)) {
		if ($lnk)  $array[$i]='<a href="'.$lnk.$array[$i].'">'.$sifrarnik[$array[$i]].'</a>';
		else $array[$i]=$sifrarnik[$array[$i]];
	}
}
function getFromSif($code,&$sif,$field,$retcode=false) {
	if($code && in_array($code,array_keys($sif))) return $sif[$code]->$field;
	else return $retcode?$code:'';
}
function simMakeInfNestedArray(&$rows,$keyFieldName) {
	$array=array();
	$curr_key='';
	foreach($rows as $row) {
		if(!($curr_key==$row->$keyFieldName)) { 
			$curr_key=$row->$keyFieldName;
			$array[$curr_key]=array();
		}
		array_push($array[$curr_key],$row);
	}
	return $array;
}


function simMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL ) {
	global $simConfig_debug;
	$mail = simCreateMail( $from, $fromname, $subject, $body );

	// activate HTML formatted emails
	if ( $mode ) {
		$mail->IsHTML(true);
	}

	if( is_array($recipient) ) {
		foreach ($recipient as $to) {
			$mail->AddAddress($to);
		}
	} else foreach (explode(";",$recipient) as $to) {
		$mail->AddAddress($to);
	}
	if (isset($cc)) {
	    if( is_array($cc) )
	        foreach ($cc as $to) $mail->AddCC($to);
	    else
	        $mail->AddCC($cc);
	}
	if (isset($bcc)) {
	    if( is_array($bcc) )
	        foreach ($bcc as $to) $mail->AddCC($to);
	    else
	        $mail->AddCC($bcc);
	}
    if ($attachment) {
        if ( is_array($attachment) )
            foreach ($attachment as $fname) $mail->AddAttachment($fname);
        else
            $mail->AddAttachment($attachment);
    } // if
	$mailssend = $mail->Send();

	
	if( $mail->error_count > 0 ) {
		//$simDebug->message( "The mail message $fromname <$from> about $subject to $recipient <b>failed</b><br /><pre>$body</pre>", false );
		//echo "Mailer Error: " . $mail->ErrorInfo . "" ;
	}
	return $mailssend;
} 

function simCreateMail( $from='', $fromname='', $subject, $body ) {
	global $simConfig_absolute_path, $simConfig_sendmail;
	global $simConfig_smtpauth, $simConfig_smtpuser;
	global $simConfig_smtppass, $simConfig_smtphost;
	global $simConfig_mailfrom, $simConfig_fromname, $simConfig_mailer;

	require_once( "phpmailer/class.phpmailer.php" );
	$mail = new simPHPMailer();

	$mail->PluginDir = $simConfig_absolute_path .'/inc/phpmailer/';
	//$mail->SetLanguage( 'en', $simConfig_absolute_path . '/includes/phpmailer/language/' );
	$mail->SetLanguage( 'hr', $simConfig_absolute_path . '/inc/phpmailer/language/' );
	$mail->CharSet 	= substr_replace(_ISO, '', 0, 8);
	$mail->IsMail();
	$tmpFrom 	= $from ? $from : $simConfig_mailfrom;
	//$mail->From 	= $simConfig_mailfrom;
	$mail->From 	= $tmpFrom;
	//$mail->ReplyTo 	= array($tmpFrom);
	$mail->FromName = $fromname ? $fromname : $simConfig_fromname;
	$mail->AddReplyTo($tmpFrom,$mail->FromName);
	//$mail->AddReplyTo('jojox@jojo.hr','jojox');
	$mail->Mailer 	= $simConfig_mailer;

	// Add smtp values if needed
	if ( $simConfig_mailer == 'smtp' ) {
		$mail->SMTPAuth = $simConfig_smtpauth;
		$mail->Username = $simConfig_smtpuser;
		$mail->Password = $simConfig_smtppass;
		$mail->Host 	= $simConfig_smtphost;
	} else

	// Set sendmail path
	if ( $simConfig_mailer == 'sendmail' ) {
		if (isset($simConfig_sendmail))
			$mail->Sendmail = $simConfig_sendmail;
	} // if

	$mail->Subject 	= $subject;
	$mail->Body 	= $body;

	return $mail;
}

function simProcessRadio(&$tmpl,$t, $nmvar, $val) {
	if ($val) { 
		$tmpl->addVar($t,$nmvar."0",'');
		$tmpl->addVar($t,$nmvar."1",'checked');
	} else {
		$tmpl->addVar($t,$nmvar."0",'checked');
		$tmpl->addVar($t,$nmvar."1",'');
	}
}

function simConvertLangConsts(&$tmpl,$t,$pref="_") {
	foreach (get_defined_constants() as $k => $v) 
		if (substr($k,0,strlen($pref))==$pref) 
			//if ($tmpl->placeholderExists($k, $t)) 		
					$tmpl->addVar($t,$k,$v);
		
}


function simDateTimeToLocal($ts) {
	global $lang_weekday,$lang_month,$_local_weekday,$_local_month;
	$ret=$ts; $i=0;
	foreach($_local_month as $m) {
		$ret=str_replace($m,$lang_month[$i],$ret);
		$i++;
	}
	$i=0;
	foreach($_local_weekday as $w) {
		$ret=str_replace($w,$lang_weekday[$i],$ret);
		$i++;
	}
	return $ret;
}
function simConvertSQLDateTimeToFormat($d,$format="d.m.Y.") {
		if ($d) {
			$darr=explode(" ",$d);
			$darr1=explode("-",$darr[0]);
			if(count($darr)>1) $darr2=explode(":",$darr[1]);
			else $darr2=array(0,0,0);
			$tm=date($format,mktime(intval($darr2[0]),intval($darr2[1]),intval($darr2[2]),intval($darr1[1]),intval($darr1[2]),intval($darr1[0])));
			return simDateTimeToLocal($tm);		
		} else return false;
	}
function convertSQLDateTimeToHr($d) {
		if ($d) {
			$darr=explode(" ",$d);
			$darr1=explode("-",$darr[0]);
			if(count($darr)>1) $darr2=explode(":",$darr[1]);
			else $darr2=array(0,0,0);
			$ret=$darr1[2].".".$darr1[1].".".$darr1[0].".";
			if(count($darr)>1) $ret.=" ".$darr2[0].":".$darr2[1].":".$darr2[2];
			return $ret;
		} else return false;
	}
function convertDateToSQL($d) {
		$ret='';
		if ($d) {
			$darr=explode(".",$d);
			if (count($darr)>2) $ret= sprintf("%04d-%02d-%02d",intval($darr[2]),intval($darr[1]),intval($darr[0]));
		}
		return $ret;
	}
function makeOption( $value, $text='',&$sel,$selmark='selected',$id='',$title='') {
		$obj = new stdClass;
		$obj->id = $id;
		$obj->title = $title;
		$obj->value = $value;
		$obj->text = trim( $text ) ? $text : $value;
		$obj->sel='';
		if (is_array($sel)) { if (in_array($obj->value,$sel)) $obj->sel=$selmark; }
		else if ($sel==$obj->value) $obj->sel=$selmark;
		return $obj;
}
function simCreateMonthOptionList($sel=1,$selmark='selected') {
	global $lang_month;
	$l=simCreateArrayOptionList($lang_month,1,$sel,$selmark);
	return $l;	
}
function simCreateRangeOptionList($start,$end,$sel=0,$selmark='selected',$step=1) {
	$l=array();
	for ($i=$start;$i<($end+1);$i=$i+$step) array_push($l,makeOption($i,$i,$sel,$selmark));
	return $l;	
}
function simCreateArrayOptionList($arr,$start=-1,$sel=0,$selmark='selected',$del='|') {
	$l=array();
	$arrx=array();
	if (is_array($arr)) $arrx=$arr;
	else if (is_string($arr)) $arrx=explode($del,$arr);
	for ($i=0;$i<count($arrx);$i++) {
		if ($start>-1) $v=$start+$i;
		else $v=$arrx[$i];
		array_push($l,makeOption($v,$arrx[$i],$sel,$selmark));
	}
	return $l;	
}
function simCreateLangConstArrayOptionList($arr,$pref,&$sel,$selmark='selected',$del='|') {
	$l=array();
	$arrx=array();
	if (is_array($arr)) $arrx=$arr;
	else if (is_string($arr)) $arrx=explode($del,$arr);
	if (is_array($sel)) $selx=$sel;
	else if (is_string($sel)) $selx=explode($del,$sel);
	for ($i=0;$i<count($arrx);$i++) {
		$v=$arrx[$i];
		$n=constant($pref.$v); 
		array_push($l,makeOption($v,$n,$selx,$selmark));
	}
	return $l;	
}
function simCreateDBOptionList($arr,&$sel,$textField='title',$valField='id',$selmark='selected',$del='|') {
	$l=array();
	$arrx=array();
	if (is_array($arr)) $arrx=$arr;
	else if (is_string($arr)) $arrx=explode($del,$arr);
	if (is_array($sel)) $selx=$sel;
	else if (is_string($sel)) $selx=explode($del,$sel);
	else $selx=array($sel);
	for ($i=0;$i<count($arrx);$i++) {
		$v=$arrx[$i]->$valField;
		$n=$arrx[$i]->$textField;
		array_push($l,makeOption($v,$n,$selx,$selmark));
	}
	return $l;	
}
function simCreateSimpleOptionList($arr,&$sel,$selmark='selected',$del='|') {
	$l=array();
	$arrx=array();
	$arrx=explode($del,$arr);
	if (is_array($sel)) $selx=$sel;
	else if (is_string($sel)) $selx=explode($del,$sel);
	for ($i=0;$i<count($arrx);$i++) {
		$v=$arrx[$i];
		array_push($l,makeOption($v,$v,$selx,$selmark));
	}
	return $l;	
}
function getSimpleOptionsString($name,$field,$selfld,$sep='|') {
		$opts='';
		foreach(explode($sep,$field) as $n) $opts.='<option value="'.trim($n).'" '.((trim($n)==trim($selfld)) ? 'selected' : '').'>'.trim($n).'</option>';
		return '<select name="'.$name.'" />'.$opts.'</select>';
}

function simModuleShowtest($vars) {
	$showtestvars=explode("|",$vars);
	$opt=$showtestvars[0];
	if (is_file('opt/'.$opt.'/common/showtest.php')) {
		include('opt/'.$opt.'/common/showtest.php');
		return $show;
	} else return false;
}

function convertPostArray($fld,$delim="|") {
	$ret="";
	if (isset($_POST[$fld]) && is_array($_POST[$fld]) && (count($_POST[$fld])>0)) $ret=implode($delim,$_POST[$fld]);
	return $ret;
}

function putNestedArrayIntoTmpl(&$tmpl,&$arr,$tmain,$tgroup,$tsublist,$fparent,$fid,$fsub,$pref,$l) {
	for ($i=0;$i<count($arr[$l]);$i++) if(isset($arr[$arr[$l][$i]->$fid]) && count($arr[$arr[$l][$i]->$fid])) {
		putNestedArrayIntoTmpl($tmpl,$arr,$tsublist,$tgroup,$tsublist,$fparent,$fid,$fsub,$pref,$arr[$l][$i]->$fid);
		$tmpl->addObject($tsublist, $arr[$arr[$l][$i]->$fid], $pref,false);
		$tmpl->addVar($tgroup, $fid, $arr[$l][$i]->$fid); 
		$arr[$l][$i]->$fsub=$tmpl->getParsedTemplate($tgroup); 
		$tmpl->clearTemplate($tsublist); 
		$tmpl->clearTemplate($tgroup); 
	}
}

function makeSqlCM($addCM,$variable,$var1) {
	//if ($slashes) $ret="CONCAT('oncontextmenu=\"cCM(\'',".$var1.",'\',".$variable.",event"; 
	//else $ret="CONCAT('oncontextmenu=\"cCM(',".$var1.",',".$variable.",event"; 
	$allargs=func_get_args();
	$args='';
	if (count($allargs)>3)	foreach(array_slice($allargs,3) as $a) if (trim($a)) $args.=",',".$a.",'";
	$ret="CONCAT('oncontextmenu=\"cCM(',".$var1.",',\'".$variable."\',event".$args.");\"')"; 
	
	if ($addCM) { 
	if (is_string($addCM)) $ret.=" as ".$addCM;
	else $ret.=" as cm";
	}
	return $ret;	
}

function makeSqlIfCM($cond,$variable1,$var11,$var12,$var13,$variable2,$var21,$var22,$var23,$fld="cm") {
	$ret="IF(".$cond.",".makeSqlCM(false,$variable1,$var11,$var12,$var13).",".makeSqlCM(false,$variable2,$var21,$var22,$var23).") as cm ";
	return $ret;
}
function makeSqlTagID($sub,$fldid,$fld="tagid") {
	return "CONCAT('id=\"".$sub."',".$fldid.",'\"') as ".$fld." ";
}
function tmplAddConditionPairs(&$tmpl) {
	global $mainFrame;
	foreach ($mainFrame->conditionPairs as $pair) $tmpl->addVar($pair->tmpl, $pair->name, $pair->value); 
}




function convertToLatin(&$text) {
	global $conv;
	$prijevod=$text;
	
	
	$ciril=array_keys($conv);
	for ($i=0; $i<count($ciril); $i++) {
		$prijevod=str_replace($ciril[$i],$conv[$ciril[$i]],$prijevod);
	}
	return $prijevod;	
}
function convertToCiril(&$text) {
	global $conv;
	$prijevod=$text;
	$conv2=array();
	$conv2['DŽ']="Ђ";$conv2['NJ']="Њ";$conv2['LJ']="Љ";
	foreach ($conv as $k=>$v) $conv2[$v]=$k;
	$latin=array_keys($conv2);
	for ($i=0; $i<count($latin); $i++) {
		$prijevod=str_replace($latin[$i],$conv2[$latin[$i]],$prijevod);
	}
	return $prijevod;	
}

function setOrderPos(&$r,$offset=0) {
	if (count($r)) {
		$r[$offset]->orderpos=0;
		$r[count($r)-1]->orderpos=1;
	}
}

function deleteFromTextArray(&$tar,$val,$sep,$sep2='') {
		$ret=false;
		if (trim($tar)) {
			$retArr=array();
			$arr=explode($sep,trim($tar));
			foreach ($arr as $a) {
				if ($sep2) {$arr2=explode($sep2,trim($a)); $test=trim($arr2[0]); }
				else $test=trim($a);
				if (!($test==$val)) array_push($retArr,trim($a));
				else $ret=true;
			}
			$tar=implode($sep,$retArr);
		} 
		return $ret;
	}
function addToTextArray(&$tar,$val,$sep) {
		if (trim($tar)) {
			$arr=explode($sep,trim($tar));
			if(!in_array($val,$arr)) $tar.=$sep.$val;
		} else $tar=$val;
		return $tar;
	}

function deleteIndexFromTextArray(&$tar,$index,$sep) {
		$ret=false;
		if (trim($tar)) {
			$retArr=array();
			$arr=explode($sep,trim($tar));
			$i=0;
			foreach ($arr as $a) {
				$test=trim($a);
				if (!($index==$i)) array_push($retArr,trim($a));
				else $ret=true;
				$i++;
			}
			$tar=implode($sep,$retArr);
			$ret=$tar;
		} 
		return $ret;
	}

function deleteColIndexFromTextTable(&$tar,$index,$sep,$sep2) {
		$ret=false;
		if (trim($tar)) {
			$retArr=array();
			$arr=explode($sep,trim($tar));		
			foreach ($arr as $a) {
				$ta=trim($a);
				array_push($retArr,deleteIndexFromTextArray($ta,$index,$sep2));
			}
			$tar=implode($sep,$retArr);
			$ret=$tar;
		} 
		return $ret;
	}
function insertIndexAtTextArray(&$tar,$index,$sep,$val) {
		$ret='';
		if (trim($tar)) {
			$retArr=array();
			$arr=explode($sep,trim($tar));
			if ($index<0) array_push($retArr,trim($val));
			$i=0;
			foreach ($arr as $a) {
				array_push($retArr,trim($a));
				if ($index==$i) array_push($retArr,trim($val));				
				$i++;
			}
			$tar=implode($sep,$retArr);
			$ret=$tar;
		} else $tar=trim($val);
		$ret=$tar;
		return $ret;
	}
function insertColIndexAtTextArray(&$tar,$index,$sep,$sep2,$val) {
		$ret='';
		if (trim($tar)) {
			$retArr=array();
			$arr=explode($sep,trim($tar));
			foreach ($arr as $a) {
				$ta=trim($a);
				array_push($retArr,insertIndexAtTextArray($ta,$index,$sep2,$val));
			}
			$tar=implode($sep,$retArr);
			$ret=$tar;
		} else $tar=trim($val);
		$ret=$tar;
		return $ret;
	}
function pushToTag($var,$tag,$add='') {
	if (is_array($var)) {
		$ret=array();
		foreach ($var as $v) $ret[]="<$tag".($add ? " ".$add :"").">$v</$tag>";
	} else $ret="<$tag".($add ? " ".$add :"").">$var</$tag>";
	return $ret;
}
function processRequestOrdering($deftype,$defdir,$spref) {
	if(!isset($_SESSION[$spref.'_ord'])) $_SESSION[$spref.'_ord']=$deftype;
	if(!isset($_SESSION[$spref.'_dir'])) $_SESSION[$spref.'_dir']=$defdir;
	$ret=array();
	$ord=trim(simGetParam($_REQUEST,'ord',$_SESSION[$spref.'_ord'])); 
	if(!$ord) $ord=$_SESSION[$spref.'_ord'];
	$dir=trim(simGetParam($_REQUEST,'dir',$_SESSION[$spref.'_dir']));
	if (!$dir) $dir=$_SESSION[$spref.'_dir'];
	if ($dir=="ASC") $sdir="DESC"; else $sdir="ASC";
	$ret['type']=$ord; 	$ret['dir']=$dir;	$ret['sdir']=$sdir;
	if (!($_SESSION[$spref.'_ord']==$ord)) $_SESSION[$spref.'_ord']=$ord;
	if (!($_SESSION[$spref.'_dir']==$dir)) $_SESSION[$spref.'_dir']=$dir;
	return $ret;	
}

function createTableView($tmplname,$name,&$rows,&$pagination,$fields,$CM,$fieldsForCM='id') {
	global $mainFrame,$tmpl;
	
	if (!is_array($fields)) $fldArr0=explode(',',$fields);
	else $fldArr0=$fields;
	
	$fldArr=array();
	$ttlArrAssoc=array();
	$addArrAssoc=array();
	foreach($fldArr0 as $f) {
		$fSplitted=explode('|',$f);
		$fldArr[]=trim($fSplitted[0]);
		if (count($fSplitted)>1) $ttlArrAssoc[$fSplitted[0]] = trim(str_replace("'","\\'",$fSplitted[1]));	
		if (count($fSplitted)>2) 
			for ($i=2;$i<count($fSplitted); $i++) if (trim($fSplitted[$i])) {
				$pair=explode(':',$fSplitted[$i]);
				if (!in_array($pair[0],array_keys($addArrAssoc))) $addArrAssoc[$pair[0]]=array();
				$addArrAssoc[$pair[0]][$fSplitted[0]] = trim($pair[1]);
			}
			
	}
	$fldArrAssoc=array_flip($fldArr);
	$cmArr=explode(',',$fieldsForCM);
	
	$flsArrPrint=array();
	$ttlArrPrint=array();
	foreach ($fldArr as $fa) $flsArrPrint[]="'".$fa."'";
	foreach ($ttlArrAssoc as $k=>$v) $ttlArrPrint[]="t_".$fldArrAssoc[$k].":'".$v."'";
	foreach ($addArrAssoc as $ka=>$va) 
		foreach ($va as $k=>$v) if ($v) $ttlArrPrint[]=$ka."_".$fldArrAssoc[$k].":'".$v."'";
	for($i=0;$i<count($cmArr);$i++) $cmArr[$i]=$fldArrAssoc[$cmArr[$i]];
	
	$tblrows=array();
	foreach ($rows as $r) {
		$rfArr=array();
		foreach ($fldArr as $f) $rfArr[]="'".str_replace("'","\\'",$r->$f)."'";
		$tblrows[]='['.implode(',',$rfArr)."]";
	}
	
	$mainFrame->addHeaderScript("var ".$name."CMmain='".$CM."';",$name."CMmain");
	$mainFrame->addHeaderScript("var ".$name."CM=[".implode(',',$cmArr)."];",$name."CM");
	$mainFrame->addHeaderScript("var ".$name."head={".implode(',',$ttlArrPrint)."};",$name."head");
	$mainFrame->addHeaderScript("var ".$name."fields=[".implode(',',$flsArrPrint)."];",$name."fields");
	$mainFrame->addHeaderScript("var ".$name."=[".implode(',',$tblrows)."];",$name);
	if (isset($pagination)) {
		$mainFrame->addHeaderScript("var ".$name."Pagination=[".$pagination->totalPages.",".$pagination->totalRows.",'".$pagination->link."',".$pagination->page.",".$pagination->pageLimit.",".$pagination->maxPagesOffset."];",$name."Pagination");
	}
	
	$mainFrame->addBodyAction("onLoad","createAppTableBlock('".$name."');");
	
	$tmpl->addVar($tmplname,$name, '<div id="'.$name.'_p_head"></div><div id="'.$name.'" style="display:block"></div><div id="'.$name.'_p_pages"></div>'); 

}
function createAlphaNumLinks($lnk) {
	$alnarr=array('0','1','2','3','4','5','6','7','8','9',
	'a','b','c','č','ć','d','dž','đ','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','š','t','u','v','x','y','w','z','ž');
	$ret=array();
	foreach ($alnarr as $a) {
		$pom=new stdClass;
		$pom->alpha=$a;
		$pom->link=$lnk.'&amp;alpha='.$a;
		$ret[]=$pom;
	}
		$pom=new stdClass;
		$pom->alpha="Prikaži sve";
		$pom->link=$lnk;
		$ret[]=$pom;
		
	return $ret;
}
function fillnum($num) {
	if ($num<10) return '0'.$num;
	else return $num;
}

$replaceCharsArray=array(
" "=>"-",
"ć"=>"c",
"č"=>"c",
"š"=>"s",
"đ"=>"d",
"ž"=>"z",
"Č"=>"C",
"Ć"=>"C",
"Š"=>"S",
"Đ"=>"D",
"Ž"=>"Z"
);
$allowedChars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_./";
$replaceCharsArray=array(
"č"=>"c","ć"=>"c","š"=>"s","đ"=>"dj","ž"=>"z","Č"=>"C","Ć"=>"C","Š"=>"S","Đ"=>"DJ","Ž"=>"Z",
"-"=>"_",","=>"_"," "=>"_","["=>"_","]"=>"_","("=>"_",")"=>"_","{"=>"_","}"=>"_","&"=>"_",
"="=>"_","+"=>"_","#"=>"_","$"=>"_","%"=>"_","!"=>"_","?"=>"_","\""=>"_"
);
function clearFilename($n,$allString='') {
	
	global $replaceCharsArray,$allowedChars;
	$aArr=$allString?$allString:$allowedChars;

	foreach(array_keys($replaceCharsArray) as $k) $n=str_replace($k,$replaceCharsArray[$k],$n);

	$i=0;
	while($i<strlen($n)) {
		$char=substr($n,$i,1);
		if (!substr_count($aArr,$char)) $n=str_replace($char,'',$n);
		else $i++;
		
	}
	$n=preg_replace('/\-\-+/', '-', $n);
	return trim($n,"_");
}
function clearJah($val) {
	$ret=str_replace("%$#","&",$val);
	$ret=str_replace("*7*8*7*6*","+",$ret);
	return $ret;
}
function standardizeAuthorField($fld,$conv=true) {
	$a=trim($fld);
	while (substr_count( $a , '  ')) $a=str_replace('  ',' ',$a);
 	$a=$conv ? iconv('UTF-8',"windows-1250",trim($a)) : trim($a);
	$a=trim($a,",");$a=str_replace("/",",",$a);$a=str_replace("|",",",$a);
	$list=array();
	foreach(explode(",",$a) as $el) {		
		$el=trim($el," ()");
		$parts=explode(" ",$el);
		$ret='';
		if ((count($parts)>1) && (strlen($parts[0])>1)) 
			for ($i=0;$i<count($parts)-1;$i++) $ret.=substr($parts[$i],0,1).".";
		$ret.=$parts[count($parts)-1]; 
		if (trim($ret) && !in_array($ret,$list)) $list[]=trim($ret);
	}
	return $conv ? trim(iconv("windows-1250",'UTF-8',implode(",",$list))) : trim(implode(",",$list));
}

function isJMBG($txt) {
	if (strlen($txt) != 13) return false;
	if (!is_numeric($txt)) return false;
	
	$sum=0;
	for($i=0;$i<6;$i++) $sum+=(7-$i)*(substr($txt,$i,1)*1 + substr($txt,6+$i,1)*1);
	$ostatak = $sum % 11;
	$razlika = 11 - $ostatak;

	if ($ostatak == 1) return false;
	else if (($ostatak == 0) && (substr($txt,12,1)*1)) return false;
	else if (!($razlika==substr($txt,12,1)*1)) return false;
	else return true;
}
function isOIB($oib) {
	if (!(strlen($oib) == 11)) return false;
	if (!is_numeric($oib)) return false;
	$a = 10;
	for ($i = 0; $i < 10; $i++) {
		$a=$a+substr($oib,$i,1)*1;
		$a=$a % 10;
		if($a==0) $a=10;
		$a *= 2;
		$a = $a % 11;
	}
	$kon = 11 - $a;
	if ($kon == 10) $kon = 0;
	return $kon == substr($oib,10, 1)*1;
}
function isEmail($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }
function createSimPath($fold,$sub='',$group='File') {
		global $simConfig_absolute_path;
		$path=$simConfig_absolute_path."/files/$group/".($sub?$sub."/":'');
		$fA=explode("/",$fold);
		$p=$path;
		foreach($fA as $f) {
			if (!is_dir($p.$f)) mkdir($p.$f);
			$p.=$f."/";
		}
		return $path.$fold;
	}
function generateUsername($i,$p,$x='') {
		$ach="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$ime=preg_replace('/\W/', '', strtolower(trim(clearFilename($i,$ach))));
		$prezime=preg_replace('/\W/', '', strtolower(trim(clearFilename($p,$ach))));
		$uname=substr($ime,0,1).$prezime;
		if ($x) $uname.=$x;
		return $uname;
	}
function generatePassword($length=8) {
		$chars = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
		$password = "";
		for ($i=0;$i <= $length;$i++) $password .= $chars{mt_rand(0,strlen($chars)-1)};
		return $password;
}
function simRmDir($pth) {
		$d = dir($pth);
		while (false !== ($entry = $d->read())) 
			if(!is_dir($pth.'/'.$entry) && substr($entry,0,1) != '.') unlink($pth.'/'.$entry); 
			else if (is_dir($pth.'/'.$entry)) {
				simRmDir($pth.'/'.$entry);
				rmdir($pth.'/'.$entry);
			}
		$d->close();
}
function getUTFName($str) {
	return _WIN?iconv("windows-1250",'UTF-8',$str):$str;
	
}
function getSysName($str) {
	return _WIN?iconv('UTF-8',"windows-1250",$str):$str;
}
function delFolder($str) {
	if(_DEL) rmdir($str);
}
function delFile($str) {
	if(_DEL) unlink($str);
}
function prepareRar($p,$n) {
	$codedfolder=_WIN?iconv('windows-1250','CP852//TRANSLIT//IGNORE',$p):$p;
	$codedname=_WIN?iconv('UTF-8','CP852//TRANSLIT//IGNORE',$n):$n;
	return $codedfolder."/".$codedname;
}
function prepareZip($p_event, &$h) {
   $info = pathinfo($h['filename']);

    $h['filename']=_WIN?iconv('CP852','windows-1250//TRANSLIT//IGNORE',$h['filename'])
					   :iconv('CP852','UTF-8//TRANSLIT//IGNORE',$h['filename']);
    return 1;
}
function prepareZipPath($str) {
	return _WIN?iconv('windows-1250','CP852//TRANSLIT//IGNORE',$str)
			   :iconv('UTF-8','CP852//TRANSLIT//IGNORE',$str);
}
function processTime(&$row,$fld,&$VAR,$def='NULL') {
		if (isset($VAR[$fld.'_sec'])) {
			$sec=intval(simGetParam($VAR,$fld.'_sec','0'));
			$min=intval(simGetParam($VAR,$fld.'_min','0'));
			$sat=intval(simGetParam($VAR,$fld.'_sat','0'));
			$ssec=$sec % 60;
			$smin=(($min % 60 )+floor($sec/60)) % 60;
			$ssat=floor($min/60 + $sec/3600) + $sat;
			if ($min || $sec || $ssat) $row->$fld=sprintf("%02d:%02d:%02d",$ssat,$smin,$ssec);
			else  $row->$fld=$def;
		}
}
function processHRDate(&$row,$fld,&$VAR,$def='NULL') {
		if (isset($VAR[$fld.'_dan'])) {
			$dan=intval(simGetParam($VAR,$fld.'_dan','0'));
			$mjesec=intval(simGetParam($VAR,$fld.'_mjesec','0'));
			$godina=intval(simGetParam($VAR,$fld.'_godina','0'));
			if ($dan && $mjesec && $godina) $row->$fld=sprintf("%04d-%02d-%02d",$godina,$mjesec,$dan);
			else $row->$fld=$def;
		}
}
function processHRPickupDateTime(&$row,$fld,&$VAR,$def='NULL') {
		$row->$fld=convertDateToSQL($row->$fld);
		if (isset($VAR[$fld.'_min'])) {
			$sec=intval(simGetParam($VAR,$fld.'_sec','0'));
			$min=intval(simGetParam($VAR,$fld.'_min','0'));
			$sat=intval(simGetParam($VAR,$fld.'_sat','0'));
			$ssec=$sec % 60;
			$smin=(($min % 60 )+floor($sec/60)) % 60;
			$ssat=floor($min/60 + $sec/3600) + $sat;
			if ($min || $sec || $ssat) $time=sprintf("%02d:%02d:%02d",$ssat,$smin,$ssec);
			else $time='';
			$row->$fld.=' '.$time;
		}
		if(!trim($row->$fld)) $row->$fld=$def;
}
function createLengthDependedCondition($fields,$src,$seps='-. (',$l0=3,$l1=6) {
	$fA=explode(",",$fields);
	$sA=array('');
	for($i=0;$i<strlen($seps);$i++) $sA[]='%'.substr($seps,$i,1);
	$ret=array();
	if(strlen($src)>$l0) foreach($fA as $f) $ret[]="$f LIKE '%$src%'";
	//if(strlen($src)>$l1) foreach($fA as $f) $ret[]="$f LIKE '%$src%'";
	//else if(strlen($src)>$l0) foreach($fA as $f) foreach($sA as $s) $ret[]="$f LIKE '$s$src%'";
	else foreach($fA as $f) if(preg_match('/%|_/',$src)) $ret[]="$f LIKE '$src'"; else $ret[]="$f='$src'";
	return implode(" OR ",$ret);
}
function createSimpleCondition($fields,$src,$start=0) {
	$fA=explode(",",$fields);
	$ret=array();
	foreach($fA as $f) {
			if(!$start) $ret[]="$f LIKE '%$src%'";
			else $ret[]="$f LIKE '$src%'";
	}
	return implode(" OR ",$ret);
}
function objToString($obj,$flds='',$del1="\n",$del2='=') {
	$ret='';
	$fldArr=array();
	if(!$flds) $fldArr=get_object_vars($obj);
	else foreach(implode(',',$flds) as $f) $fldArr[$f]=$obj->$f;
	foreach($fldArr as $k=>$v) $ret.=$k.$del2.$v.$del1;
	return trim($ret);
	

}
function objToXml($obj,$tagname='result',$flds='') {
	$ret='';
	$fldArr=array();
	if(is_object($obj)) {
		if(!$flds) $fldArr=get_object_vars($obj);
		else foreach(implode(',',$flds) as $f) $fldArr[$f]=$obj->$f;
		foreach($fldArr as $k=>$v) $ret.="<$k>$v</$k>";
		$ret= "<$tagname>".trim($ret)."</$tagname>";
	} 
	return $ret;
}
function inArray($list,$flds,$sep=','){
	$ret=false;
	if(is_array($list)) $lArr=$list;
	else if(is_string($list)) $lArr=explode($sep,$list);
	else $lArr=array($list);
	$i=0;
	while(!$ret && (count($lArr)>$i++)) if(in_array($lArr[$i-1],$flds)) $ret=true;

	return $ret;
}
function transliteracija_nehr($s) {
    $replace = array(
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ă'=>'A', 'Ą'=>'A', 'Ā'=>'A', 
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'ae','ă'=>'a', 'ą'=>'a', 'ā'=>'a',
        'þ'=>'b', 'Þ'=>'B',
        'Ç'=>'C', 'ç'=>'c', // 'Ć' => 'C', 'ć' => 'c', 'Č' => 'C', 'č' => 'c',
        //'Đ'=>'DJ', 'đ'=>'dj',
        'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ę'=>'E', 'Ė'=>'E',
        'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ę'=>'e', 'ė'=>'e',
        'Ğ'=>'G', 'ğ'=>'g',
        'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'İ'=>'I', 'Ī'=>'I',
        'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ı'=>'i', 'ī'=>'i',
        'Ł' => 'L', 'ł' => 'l',
        'Ñ'=>'N', 'Ń' => 'N', 'ń' => 'n', 'ñ'=>'n',
        'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ō'=>'O',
        'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ō'=>'o', 'ð'=>'o',
        'Ş'=>'S', 'ș'=>'s', 'Ș'=>'S', 'ş'=>'s', 'ß'=>'ss', 'Ś' => 'S', 'ś' => 's', //'Š'=>'S', 'š'=>'s',
        'ț'=>'t', 'Ț'=>'T',
        'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ū'=>'U',
        'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ū'=>'u',
        'Ý'=>'Y',
        'ý'=>'y', 'ý'=>'y', 'ÿ'=>'y',
        'Ż' => 'Z', 'ż' => 'z', 'Ź' => 'Z', 'ź' => 'z', //'Ž'=>'Z', 'ž'=>'z'
    );
    return strtr($s, $replace);
}
function makeUSFloat($str,$dec=-1) {
	$ret=1*(substr_count($str,",")?str_replace(',','.',str_replace(".","",$str)):$str);
	if($dec>-1) return str_replace(",",".",round($ret,$dec));
	else return str_replace(",",".",$ret);
}

function makeHRFloat($str,$sufix='',$printZero=false) {
	if (floatval($str)>0) return str_replace("#",",",str_replace(",",".",str_replace(".","#",number_format($str,2)))).$sufix;
	else if ($printZero) return '0,00'.$sufix;
	else return '';
}

function reindex(&$arr,$fld) {
	$arr2=array();
	foreach($arr as $k=>$v) $arr2[$v->$fld]=$v;
	return $arr2;
}
function checkIBAN($iban) {
    $iban = strtolower(str_replace(' ','',$iban));
    $Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
    $Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);

    if(strlen($iban) == $Countries[substr($iban,0,2)]){

        $MovedChar = substr($iban, 4).substr($iban,0,4);
        $MovedCharArray = str_split($MovedChar);
        $NewString = "";

        foreach($MovedCharArray AS $key => $value){
            if(!is_numeric($MovedCharArray[$key])){
                $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
            }
            $NewString .= $MovedCharArray[$key];
        }

        if(bcmod($NewString, '97') == 1)
        {
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    else{
        return FALSE;
    }   
}
function auto_version($file)
{
  global $simConfig_absolute_path;
  if(!file_exists($simConfig_absolute_path."/". $file))
    return $file;

  $mtime = filemtime($simConfig_absolute_path."/". $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
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
function getGUID() {
    if (function_exists('com_create_guid')) {
	return trim(com_create_guid(), '{}');
    } else {
        mt_srand((double)microtime()*10000); //optional for php 4.2.0 and up.
        //$charid = strtoupper(md5(uniqid(rand(), true)));
        $charid = md5(uniqid(rand(), true));
        $hyphen = chr(45); // "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
	return trim($uuid, '{}');
    }
}
?>