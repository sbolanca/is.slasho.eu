<?php



class database {
	var $_sql='';
	var $_errorNum=0;
	var $_errorMsg='';
	var $_resource='';
	var $_cursor=null;
	var $_debug=0;
	var $_ticker=0;
	var $_log=null;
	var $_connected=false;

	function database( $host='localhost', $user, $pass, $db,$offline=true ) {
		if (!function_exists( 'mysqli_connect' )) {
			$simSystemError = 1;
			$basePath = dirname( __FILE__ );
			if($offline) {
				include $basePath . '/../configuration.php';
				include $basePath . '/../offline.php';
				exit();
			} else 	return false;
		}
		if (!($this->_resource = @mysqli_connect( $host, $user, $pass,$db ))) {
			$simSystemError = 2;
			$basePath = dirname( __FILE__ );
			if($offline) {
				include $basePath . '/../configuration.php';
				include $basePath . '/../offline.php';
				exit();
			} else 	return false;
		}
		/*
		if (!mysqli_select_db($db,$this->_resource)) {
			$simSystemError = 3;
			$basePath = dirname( __FILE__ );
			if($offline) {
				include $basePath . '/../configuration.php';
				include $basePath . '/../offline.php';
				exit();
			} else 	return false;
		}
		*/
		 mysqli_query($this->_resource,"SET NAMES 'utf8'");
         mysqli_query($this->_resource,"SET CHARCTER SET utf8");
         mysqli_query($this->_resource,"SET COLLATION_CONNECTION='utf8_croatian_ci'");
		$this->_ticker = 0;
		$this->_log = array();
		$this->_connected=true;
	}
	function debug( $level ) {
	    $this->_debug = intval( $level );
	}
	function getErrorNum() {
		return $this->_errorNum;
	}
	function getErrorMsg() {
		return str_replace( array( "\n", "'" ), array( '\n', "\'" ), $this->_errorMsg );
	}
	function getEscaped( $text ) {
		return mysqli_escape_string($this->_resource, $text );
	}
	function Quote( $text ) {
		return '\'' . mysqli_escape_string($this->_resource, $text ) . '\'';
	}
	function setQuery( $sql, $prefix='' ) {
	    $sql = trim( $sql );
		$this->_sql = $sql;
	}
	function getQuery() {
		return "<pre>" . htmlspecialchars( $this->_sql ) . "</pre>";
	}
	function query($multi=false) {
		global $simConfig_debug;
		if ($this->_debug) {
			$this->_ticker++;
	  		$this->_log[] = $this->_sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if($multi) {
			if(mysqli_multi_query( $this->_resource,$this->_sql )) {
				do {
					$this->_cursor = mysqli_store_result($this->_resource);
				} while (mysqli_next_result($this->_resource));
			} else $this->_cursor = false;
		} else $this->_cursor = mysqli_query( $this->_resource,$this->_sql );
		if (!$this->_cursor) {
			$this->_errorNum = mysqli_errno( $this->_resource );
			$this->_errorMsg = mysqli_error( $this->_resource )." SQL=$this->_sql";
			if ($this->_debug) {
				trigger_error( mysqli_error( $this->_resource ), E_USER_NOTICE );
				//echo "<pre>" . $this->_sql . "</pre>\n";
				if (function_exists( 'debug_backtrace' )) {
					foreach( debug_backtrace() as $back) {
					    if (@$back['file']) {
						    echo '<br />'.$back['file'].':'.$back['line'];
						}
					}
				}
			}
			return false;
		}
		return $this->_cursor;
	}

	function query_batch( $abort_on_error=true, $p_transaction_safe = false) {
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$si = mysqli_get_server_info($this->_resource);
			preg_match_all( "/(\d+)\.(\d+)\.(\d+)/i", $si, $m );
			if ($m[1] >= 4) {
				$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
			}
		}
		$query_split = preg_split ("/[;]+/", $this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim( $command_line );
			if ($command_line != '') {
				$this->_cursor = mysqli_query($this->_resource, $command_line );
				if (!$this->_cursor) {
					$error = 1; echo 'xxx ';
					$this->_errorNum .= mysqli_errno( $this->_resource ) . ' ';
					$this->_errorMsg .= mysqli_error( $this->_resource )." SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}
	function explain() {
		$temp = $this->_sql;
		$this->_sql = "EXPLAIN $this->_sql";
		$this->query();

		if (!($cur = $this->query())) {
			return null;
		}
		$first = true;

		$buf = "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" bgcolor=\"#000000\" align=\"center\">";
		$buf .= $this->getQuery();
		while ($row = mysqli_fetch_assoc( $cur )) {
			if ($first) {
				$buf .= "<tr>";
				foreach ($row as $k=>$v) {
					$buf .= "<th bgcolor=\"#ffffff\">$k</th>";
				}
				$buf .= "</tr>";
				$first = false;
			}
			$buf .= "<tr>";
			foreach ($row as $k=>$v) {
				$buf .= "<td bgcolor=\"#ffffff\">$v</td>";
			}
			$buf .= "</tr>";
		}
		$buf .= "</table><br />&nbsp;";
		mysqli_free_result( $cur );

		$this->_sql = $temp;

		return "<div style=\"background-color:#FFFFCC\" align=\"left\">$buf</div>";
	}
	function getNumRows( $cur=null ) {
		return mysqli_num_rows( $cur ? $cur : $this->_cursor );
	}
	function execQuery($q,$multi=false) {
		$this->setQuery($q);
		return $this->query($multi);
	}
	function loadResult() {
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysqli_fetch_row( $cur )) {
			$ret = $row[0];
		}
		mysqli_free_result( $cur );
		return $ret;
	}
	function getResult($q) {
		$this->setQuery($q);
		return $this->loadResult();
	}

	function loadResultArray($numinarray = 0) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row( $cur )) {
			$array[] = $row[$numinarray];
		}
		mysqli_free_result( $cur );
		return $array;
	}
	function loadResultArrayText($q,$separator=',',$numinarray = 0) {
		$this->setQuery($q);
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row( $cur )) {
			$array[] = $row[$numinarray];
		}
		mysqli_free_result( $cur );
		return trim(implode($separator,$array));
	}
	function loadAssocList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_assoc( $cur )) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result( $cur );
		return $array;
	}
	function getSimpleList($table,$value='',$key='id',$cond='') {
		if (!trim($value)) $value=trim($table);
		if (trim($cond)) $cond=" WHERE ".$cond;
		$this->setQuery("SELECT $key, $value FROM $table $cond");
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row( $cur )) {
			$array[$row[0]] = $row[1];
		}
		mysqli_free_result( $cur );
		return $array;
	}
	function getSimpleListFromQuery($q) {
		$this->setQuery($q);
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_row( $cur )) {
			$array[$row[0]] = $row[1];
		}
		mysqli_free_result( $cur );
		return $array;
	}
	function loadObject( &$object ) {
		if ($object != null) {
			if (!($cur = $this->query())) {
				return false;
			}
			if ($array = mysqli_fetch_assoc( $cur )) {
				mysqli_free_result( $cur );
				simBindArrayToObject( $array, $object, null, null, false );
				return true;
			} else {
				return false;
			}
		} else {
			if ($cur = $this->query()) {
				if ($object = mysqli_fetch_object( $cur )) {
					mysqli_free_result( $cur );
					return true;
				} else {
					$object = null;
					return false;
				}
			} else {
				return false;
			}
		}
	}
	function loadObjectList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_object( $cur )) {
			if ($key) {
				$array[$row->$key] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result( $cur );
		return $array;
	}
	function loadRow() {
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($row = mysqli_fetch_row( $cur )) {
			$ret = $row;
		}
		mysqli_free_result( $cur );
		return $ret;
	}
	function loadRowList( $key='' ) {
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_array( $cur )) {
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
		mysqli_free_result( $cur );
		return $array;
	}

	function insertObject( $table, &$object, $keyName = NULL,  $verbose=false, $autoincrement=true ) {
		$fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";
		
		$fields = array();
		$where = array();
		$values = array();

		$keys=explode(',',$keyName);
		
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL or ($v=='NULL')) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			if(in_array($k, $keys )) { 
				$where[] = "$k='" . $this->getEscaped( $v ) . "'";
			}
			$fields[] = "$k";
			if (isset($object->_upperTextFields) && is_array($object->_upperTextFields) && in_array($k,$object->_upperTextFields)) $values[] ="UPPER('" . $this->getEscaped( $v ) . "')";
			else $values[] = "'" . $this->getEscaped( $v ) . "'";

		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		($verbose) && print "$sql<br />\n";
		if (!$this->query()) {
			return false;
		}
		$id = mysqli_insert_id($this->_resource);
		($verbose) && print "id=[$id]<br />\n";
		if ($keyName && ($id || !$autoincrement)) {
			if ($id) $object->$keyName = $id;
			
		}
		return true;
	}

	
	function updateObject( $table, &$object, $keyName, $updateNulls=true ) {
		$fmtsql = "UPDATE $table SET %s WHERE %s";
		$tmp = array();
		$tmp2 = array();
		$where = array();
		$keys=explode(',',$keyName);
		foreach (get_object_vars( $object ) as $k => $v) {
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if(in_array($k, $keys )) { // PK not to be updated
				$where[] = "$k='" . $this->getEscaped( $v ) . "'";
				continue;
			}
			if ($v === NULL && !$updateNulls) {
				continue;
			}
			if ($v=='NULL') {
				$val='NULL';
			} else if( $v == '' ) {
				$val = "''";
			} else 	if (isset($object->_upperTextFields) && is_array($object->_upperTextFields) && in_array($k,$object->_upperTextFields)) {
					$val ="UPPER('" . $this->getEscaped( $v ) . "')";
			} else {
				$val = "'" . $this->getEscaped( $v ) . "'";
			}			
			$tmp[] = "`$k`=$val";
			$tmp2[]="$k";
		}
		
		
		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , implode(" AND ",$where ) ) );
		$ret= $this->query();
		
		return $ret;
	}

	function stderr( $showSQL = false ) {
		return "DB function failed with error number $this->_errorNum"
		."<br /><font color=\"red\">$this->_errorMsg</font>"
		.($showSQL ? "<br />SQL = <pre>$this->_sql</pre>" : '');
	}

	function insertid()
	{
		return mysqli_insert_id($this->_resource);
	}

	function getVersion()
	{
		return mysqli_get_server_info($this->_resource);
	}

	function GenID( $foo1=null, $foo2=null ) {
		return '0';
	}
	function getTableList() {
		$this->setQuery( 'SHOW tables' );
		$this->query();
		return $this->loadResultArray();
	}
	function getTableCreate( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW CREATE table ' . $tblval );
			$this->query();
			$result[$tblval] = $this->loadResultArray( 1 );
		}

		return $result;
	}
	function getTableFields( $tables ) {
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( 'SHOW FIELDS FROM ' . $tblval );
			$this->query();
			$fields = $this->loadObjectList();
			foreach ($fields as $field) {
				$result[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type );
			}
		}

		return $result;
	}
	
	function rebuildConnectionTable($tbl,$fld,$fixflds,$fixvals,&$newValuesArray,$condition='') {
		
		$ret=true;
		
		if (!is_array($fixflds)) $fixflds=explode(',',$fixflds);
		if (!is_array($fixvals)) $fixvals=explode(',',$fixvals);
		
		
		if (!count($fixflds) || !(count($fixflds)==count($fixflds))) $ret=false;
		else {
		
			if (trim($condition)) $condition=" AND (".$condition.")";
			
			$fixPairs=array();
			for($i=0;$i<count($fixflds);$i++) $fixPairs[]=$fixflds[$i].'='.$fixvals[$i];
			$strFixPairs=implode(' AND ',$fixPairs);
			
			$this->setQuery("SELECT DISTINCT($fld) FROM $tbl WHERE $strFixPairs $condition");
			$oldArray=$this->loadResultArray();
			if (count($oldArray)) { 
			$this->setQuery("DELETE FROM $tbl WHERE $strFixPairs $condition "
				.(count($newValuesArray) ? "AND $fld NOT IN(".implode(',',$newValuesArray).")" : ""));
				$ret=$this->query();
			}
			
			$forInsertArray=array();
			$strfixvals=implode(',',$fixvals);
			for($i=0;$i<count($newValuesArray);$i++) 
				if (!in_array($newValuesArray[$i],$oldArray)) $forInsertArray[]="($strfixvals,".$newValuesArray[$i].')';
			
			
			if (count($forInsertArray)) {
				$strfixflds=implode(',',$fixflds);
				$this->setQuery("INSERT INTO $tbl ($strfixflds,$fld) VALUES ".implode(',',$forInsertArray));
				$ret=$this->query();
			}
		} 
		return $ret;
		
	}
}

class simDBTable {
	var $_tbl = '';
	var $_tbl_key = '';
	var $_tbl_keys=array();
	var $_error = '';
	var $_db = null;
	var $_has_autoincrement = true;
	var $_htmlFields = array();
	var $_textFields = array();
	var $_upperTextFields = array();

	function simDBTable( $table, $key, &$db ) {
		$this->_tbl = $table;
		$this->_tbl_key = $key;
		$this->_tbl_keys=explode(",",$key);
		$this->_db =& $db;
	}
	function setAsHtml($list) {
		if (is_array($list)) $procarr=$list;
		else if (is_string($list)) $procarr=explode(",",$list);
		else $procarr=array();
		foreach ($procarr as $fld) array_push($this->_htmlFields,trim($fld));	

	}
	function setAsText($list) {
		if (is_array($list)) $procarr=$list;
		else if (is_string($list)) $procarr=explode(",",$list);
		else $procarr=array();
		foreach ($procarr as $fld) array_push($this->_textFields,trim($fld));	
	}
	function setAsUpperText($list) {
		if (is_array($list)) $procarr=$list;
		else if (is_string($list)) $procarr=explode(",",$list);
		else $procarr=array();
		foreach ($procarr as $fld) array_push($this->_upperTextFields,trim($fld));	
		$this->_textFields=array_merge($this->_textFields,$this->_upperTextFields);
	}
	function addslashes($list='') {
		if (!$list) $procarr=$this->_textFields;
		else if (is_array($list)) $procarr=$list;
		else if (is_string($list)) $procarr=explode(",",$list);
		else $procarr=array();
		foreach ($procarr as $fld) if (isset($this->$fld)) $this->$fld=addslashes($this->$fld);
	}
	function processAddSlashes(&$obj) {
		foreach ($this->_textFields as $fld) if (isset($obj->$fld)) $obj->$fld=addslashes($obj->$fld);
	}
	function htmlspecialchars($list='') {
		if (!$list) $procarr=$this->_textFields;
		else if (is_array($list)) $procarr=$list;
		else if (is_string($list)) $procarr=explode(",",$list);
		else $procarr=array();
		foreach ($procarr as $fld) if (isset($this->$fld)) $this->$fld=htmlspecialchars($this->$fld);
	}
	function processHtmlSpecialChars(&$obj) {
		foreach ($this->_textFields as $fld) if (isset($obj->$fld)) $obj->$fld=htmlspecialchars($obj->$fld);
	}
	function printkeys() {
	   
		foreach ($this->_tbl_keys as $tk) echo $tk." : ".$this->$tk."<BR>";		
	}
	
	function getError() {
		return $this->_error;
	}
	
	function get( $_property ) {
		if(isset( $this->$_property )) {
			return $this->$_property;
		} else {
			return null;
		}
	}
	
	
	function set( $_property, $_value ) {
		$this->$_property = $_value;
	}

	function bind( $array, $ignore="" ) {
		if (!is_array( $array )) {
			$this->_error = strtolower(get_class( $this ))."::bind failed.";
			return false;
		} else {
			return simBindArrayToObject( $array, $this, $ignore );
		}
	}
	
	function bindObject($obj) {
		$vars=get_object_vars($this);
		//print_r(get_object_vars($this));
		foreach (get_object_vars($obj) as $k => $v) 
			if (array_key_exists($k, $vars))
					$this->$k=$v;

	}

	function extractKeysWithValues($sep=",",$pref="") {
		$kv=array();
		foreach ($this->_tbl_keys as $ka) $kv[]=$pref.$ka."='".$this->$ka."'";
		return implode($sep,$kv);
	}
	function setKeyValues($oid) {
		$procarr=array();
		if ($oid !== null)
			if (is_array($oid)) $procarr=$oid;
			else if (is_string($oid)) $procarr=explode(",",$oid);
			else if (is_integer($oid)) $procarr[0]=$oid;
			else return false;
		else return false;
		if (count($procarr)==count($this->_tbl_keys)) {
		  	$i=0;
			foreach ($this->_tbl_keys as $ka) {
				$this->$ka=$procarr[$i];
				$i++;
			}
		} 
		return true;
	}

	function testkeys() {
		$ret=true;
		foreach ($this->_tbl_keys as $ka) if (!$this->$ka) $ret=false;
		return $ret;
	}
	
	function testRecord() {
		$where=array();
		foreach ($this->_tbl_keys as $ka) $where[]="$ka='".$this->$ka."'";
		$fmtsql="SELECT count(*) FROM $this->_tbl WHERE %s";
		$this->_db->setQuery( sprintf( $fmtsql, implode(" AND ",$where ) ) );
		$cnt=$this->_db->loadResult();
		if ($cnt==1) return true;
		else return false;
	}
		
	 function load( $oid=null ) {
	  if ($oid !== null) 
			if (!($this->setKeyValues($oid))) { 
				$this->_error = "Invalid keys types: can not set keys. ";
				return false;
		}
		if (!$this->testkeys()) {
			$this->_error = "Keys values are not valid. ";
			return false;
		}
		$this->_db->setQuery( "SELECT * FROM $this->_tbl WHERE ". $this->extractKeysWithValues(" AND " ));
		$rez= $this->_db->loadObject( $this );
		return $rez;		
	}
	
	
	function convertVannaLinks($flist=0) {
		global $isAdmin,$lang;
		if (is_array($flist)) $procarr=$flist;
		else if (is_string($flist)) $procarr=explode(",",$flist);
		else $procarr=array();
		$l=$lang; 
		if (count($procarr))
			 foreach ($procarr as $fld) $this->$fld=vnnConvertVannaLinks($this->$fld,$l,$isAdmin);
		else 
			 foreach ($this->_htmlFields as $fld) $this->$fld=vnnConvertVannaLinks($this->$fld,$l,$isAdmin);
	}

	function addField($flds,$val=null) {
		$procarr=array();
		if (is_array($flds)) $procarr=$flds;
		else if (is_string($flds)) $procarr=explode(",",$flds);
		foreach($procarr as $f) { $f=trim($f); $this->$f=null; }
		if(!($val===null)) {$fld=$procarr[0];$this->$fld=$val;} 
		return count($procarr);
	}
	function unhtmlentities ($string) {
			$trans_tbl = get_html_translation_table (HTML_ENTITIES);
			$trans_tbl = array_flip ($trans_tbl);
			return strtr ($string, $trans_tbl);
	}


	function check($isJah=false) {
		foreach (get_object_vars( $this ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			if ($isJah) {
				$this->$k=str_replace("%$#","&",$this->$k);
				$this->$k=str_replace("*7*8*7*6*","+",$this->$k);
			}
		}
		//echo $_SERVER['HTTP_ACCEPT_CHARSET'];
		return true;
	}

	
	function store(  ) {
		global $migrate;
		$update=false;
		if($this->testkeys() && !$migrate) {
			  if ($this->testRecord()) $update=true;
			  else if ($this->_has_autoincrement) {
				$this->_error = strtolower(get_class( $this ))."::store failed: record does not exists !";
				return false;
			  }
			} else if (!$this->_has_autoincrement) {
				$this->_error = strtolower(get_class( $this ))."::store failed: Key is not complete";
				return false;
			} 
			//if ($update) echo "UPDATE"; else echo "INSERT";
			if ($update) $ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key,false);
			else $ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key ,false,  $this->_has_autoincrement);
			 
			if( !$ret ) {
				$this->_error = strtolower(get_class( $this ))."::store failed:  <br />" . $this->_db->getErrorMsg();
				return false;
			} else {
				return true;
			}		
	}
	
	function setNextOrdering($condition='') {
		$c= ($condition ? ' WHERE '.$condition : '');
		$o="SELECT MAX(ordering) as cnt FROM ".$this->_tbl.$c;
		$this->_db->setQuery($o);
		$this->ordering=intval($this->_db->loadResult())+1;
		return $this->ordering;
	}
	function getRecordsCount($condition='') {
		$c= ($condition ? ' WHERE '.$condition : '');
		$o="SELECT COUNT(*) as cnt FROM ".$this->_tbl.$c;
		$this->_db->setQuery($o);
		$cnt=intval($this->_db->loadResult());
		return $cnt;
	}

	function getObjectBackup() {
		$props=array_keys(get_class_vars(get_class($this)));
		$back=array();
		foreach($props as $prop) {
			if (!($prop[0]=='_') && !is_array($this->$prop) && !is_object($this->$prop)) {
				$back[$prop]=$this->$prop;				
			}
		}
		return $back;
	}
	function restoreObjectBackup(&$arr) {
		foreach(array_keys($arr) as $prop)  $this->$prop=$arr[$prop];
	}
	
	
	

	function delete( $oid=null ) {
		//if (!$this->canDelete( $msg )) {
		//	return $msg;
		//}

		if ($oid !== null) 
			if (!($this->setKeyValues($oid))) { 
				$this->_error = "Invalid keys types: can not set keys. ";
				return false;
		}
		if (!$this->testkeys()) {
			$this->_error = "Keys values are not valid. ";
			return false;
		}
		$this->_db->setQuery( "DELETE FROM $this->_tbl WHERE ". $this->extractKeysWithValues(" AND " ));
		if ($this->_db->query()) {
			return true;
		} else {
			$this->_error = $this->_db->getErrorMsg();
			return false;
		}
	}

	
	
	function convertDateToSQL($fld) {
		$d=$this->$fld;
		if ($d) {
			$darr=explode(".",$d);
			$this->$fld=$darr[2]."-".$darr[1]."-".$darr[0];
		}
	}
	function convertSQLDateToHR($fld,$time=false) {
		$d=$this->$fld;
		if ($d) {
			$darr=explode(" ",$d);
			$darr2=explode("-",$darr[0]);
			$this->$fld=$darr2[2].".".$darr2[1].".".$darr2[0].".";
			if($time && (count($darr)>1)) $this->$fld.=" ".$darr[1];
		}
	}
	function prepareSQLDateToHRPickupEdit($fld) {	
		if (trim($this->$fld)) {
			$fh=$fld."_sat";$fm=$fld."_min";$fs=$fld."_sec";
			$this->addField("$fh,$fm,$fs");
			$sArr=explode(" ",trim($this->$fld));
			$this->$fld=convertSQLDateTimeToHr($sArr[0]);
			if(count($sArr)>1){
				$this->$fh=substr($sArr[1],0,2);
				$this->$fm=substr($sArr[1],3,2);
				$this->$fs=substr($sArr[1],6,2);
			}
		}
	}
	
	function addCMField($variable,$var1='') {
	    if ($var1=='') $var1=$this->id;
		$allargs=func_get_args();
		if (count($allargs)>2)	$args=','.implode(',',array_slice($allargs,2));
		else $args='';
		$value='oncontextmenu="cCM('.$var1.',\''.$variable.'\',event'.$args.')"';
		$this->cm=$value;
	}
	function addCMValue($variable,$var1='') {
	    if ($var1=='') $var1=$this->id;
		$allargs=func_get_args();
		if (count($allargs)>2)	$args=','.implode(',',array_slice($allargs,2));
		else $args='';
		$value='cCM('.$var1.',\''.$variable.'\',event'.$args.')';
		$this->cm=$value;
	}
		
	function addTAGIDField($prefix,$id="id",$fld="tagid") {
		$value='id="'.$prefix.$this->$id.'" ';
		$this->$fld=$value;
	}
	function addMore($value) {
		$this->more=$value;
	}
	

	function save( $order_filter ) {
		if (!$this->bind( $_POST )) {
			return false;
		}
		if (!$this->check()) {
			return false;
		}
		if (!$this->store()) {
			return false;
		}
		if (!$this->checkin()) {
			return false;
		}
		$filter_value = $this->$order_filter;
		$this->updateOrder( $order_filter ? "`$order_filter`='$filter_value'" : "" );
		$this->_error = '';
		return true;
	}
	
	function findDiffs(&$obj) {
		$props=array_keys(get_class_vars(get_class($this)));
		$back=array();
		foreach($props as $prop) {
			if (!($prop[0]=='_') && !is_array($this->$prop) && !is_object($this->$prop) && !in_array($prop,$this->_tbl_keys) && !($this->$prop===null) && !($obj->$prop==$this->$prop)) {
				$back[]=$prop.": ".$obj->$prop." -> ".$this->$prop;				
			}
		}
		return implode("\n",$back);
	}
	

	function toXML( $mapKeysToText=false ) {
		$xml = '<record table="' . $this->_tbl . '"';
		if ($mapKeysToText) {
			$xml .= ' mapkeystotext="true"';
		}
		$xml .= '>';
		foreach (get_object_vars( $this ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			$xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
		}
		$xml .= '</record>';

		return $xml;
	}
	function export( $ttl="",$cls='' ) {
		if ($cls) $xml='<div class="'.$cls.'">'; else $xml = '';
		if (trim($ttl)) $xml.=$ttl."\n"; 
		
		foreach (get_object_vars( $this ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL ) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
			
			$xml .= $k . '='. $v . "\n";
		}
		if ($ttl) $xml.="\n";
		if ($cls) $xml.="</div>";
		return $xml;
	}
	function setAsNull($flds) {
		foreach(explode(",",$flds) as $fld) $this->$fld=null;
	}
	
	function manipulateSuggestion($fld,$cls,$valpost,$valfld='',$addField='') {
		if (trim($addField)) $addField=explode(',',trim($addField));
		else  $addField=array();
		if (!$valfld) $valfld=$valpost;
		$oldfld=intval(simGetParam($_REQUEST,'old_'.$fld,0));
		$createnew=intval(simGetParam($_REQUEST,'createnew'.$valpost,0));
		if (!$this->$fld) $this->$fld=0;
		if ($createnew) {
			$izv=new $cls($this->_db);
			$izv->$valfld=trim(simGetParam($_POST,$valpost,''));
			foreach($addField as $af) $izv->$af=$this->$af;
			$izv->check(true);
			$izv->store();		
			$this->$fld=$izv->id;
		} else if (!$this->$fld && $oldfld) {	
			$this->$fld=null;
			$izv=new $cls($this->_db);
			$izv->id=$oldfld;
			$izv->$valfld=trim(simGetParam($_POST,$valpost,''));
			$izv->check(true);
			$izv->store();		
		}		
	}
	
	
	function bindSuggest(&$arr,$fld,$postfld,$key='id') {
		$this->bind($arr);
		$setfld=intval(simGetParam($_REQUEST,$postfld,0));
		$oldfld=intval(simGetParam($_REQUEST,'old_'.$postfld,0));
		if (intval(simGetParam($_REQUEST,'createnew'.$fld,0))) 	$this->$key=null;
		else if ($setfld) $this->$key=$setfld;
		else $this->$key=$oldfld;
	}
	function setOrderingCode($tbl='',$id=0,$fld='id',$sep="/") {
		if (!$id) $id=$this->parentID;
		if (!$tbl) $tbl=$this->_tbl;
		if (!$fld) $fld=$this->_tbl_key;
		$this->_db->setQuery("SELECT code FROM $tbl WHERE $fld='$id'");
		$c=trim($this->_db->loadResult());
		if (!$this->ordering) $this->ordering=($this->id ? $this->id : 1);
		$this->code=($c ? $c.$sep : '' ).$this->ordering;
	}
	function setImportValues($fld,$val,&$updates,&$log) {
						if (!(trim($this->$fld)==$val)) {
							if ($this->id) $log[]=$fld.":".$this->$fld." ---> ".$val;
							$this->$fld=$val;
							$updates[]=$fld;
						}
	}
	
}


class simIzvodjacSugestionTable extends simDBTable {


	function manipulateIzvodjacSuggestion($izvodjac='',$typeVal='',$IDfldName='izvodjacID') {
		$createnewizvodjac=intval(simGetParam($_REQUEST,'createnewizvodjac',0));
		if (!$typeVal && isset($this->tip_izvodjaca)) $typeVal=$this->tip_izvodjaca;
		if (!$izvodjac && isset($this->izvodjac)) $izvodjac=$this->izvodjac;
		if ($createnewizvodjac) {
			if ($typeVal=='ONE') {
				include_once("opt/osoba/osoba.class.php");
				$izv=new simOsoba($this->_db);
				$izv->ime=$row->izvodjac;
				$izv->prezime=$izvodjac;
				$izv->punoime=$izvodjac;
				$izv->nadimak=$izvodjac;
				$splited=explode(' ',trim($izvodjac));
				if (count($splited)) $izv->ime=$splited[0];
				if (count($splited)>1) $izv->prezime=$splited[1];
				if (count($splited)>2) $izv->nadimak=$splited[2];
				$izv->check(true);
				$izv->store();
				$izv0=new simIzvodjac($this->_db);
				$izv0->id=$izv->id;
				$izv0->djelovanje='0000';
				$izv0->store();
			} else {
				include_once("opt/sastav/sastav.class.php");
				$izv=new simSastav($this->_db);
				$izv->sastav=$izvodjac;
				$izv->godina_formiranja=$this->godina;
				$izv->check(true);
				$izv->store();
			}
			$this->$IDfldName=$izv->id;
		}
	}


}








?>
