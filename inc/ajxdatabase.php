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
	function execQuery($q,$multi=false) {
		$this->setQuery($q);
		return $this->query($multi);
	}
	
	function getNumRows( $cur=null ) {
		return mysqli_num_rows( $cur ? $cur : $this->_cursor );
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
	function getSimpleListFromQuery($q,$value,$key='id') {
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
	function printRows( $fields,$cmArr='',$func='',$args='') {
		global $isJson;
		if ($isJson) $this->printJsonRows($fields,$cmArr,$func,$args);
		else $this->printXmlRows($fields,$cmArr,$func,$args);
	}
	function printXmlRows( $fields,$cmArr='',$func='',$args='') {
	global $posStart,$total_count,$count,$myID,$opt,$act,$trTag,$tdTag,$bodyTag,$tblStylesTransform,$showTableColors;
		if($posStart || $total_count>-1) $this->_sql=str_replace('SQL_CALC_FOUND_ROWS','',$this->_sql);
		if (!($cur = $this->query())) {
			return null;
		} else if(substr_count($this->_sql,'SQL_CALC_FOUND_ROWS')) {
			$count_result = mysqli_query($this->_resource,"SELECT FOUND_ROWS() cnt");
			$resc->data_seek($count_result);
			$datarow = $resc->fetch_array();
			$total_count = $datarow["cnt"];	 
		}
		//{$fp=fopen("logsql/".date("Y-m-d").".txt","a");fputs($fp,$opt."|".$act." ".date("Y-m-d H:i:s")." - $myID\n".$this->_sql."\n\n");fclose($fp); }
		
		$fields=explode(",",$fields);
		$cmArr=($cmArr ? explode(",",$cmArr) : array());
		print '<'.$bodyTag.' pos="'.$posStart.'" total_count="'.$total_count.'">';
		//print '<row id="1"><cell>'.$_GET['posStart'].'</cell><cell>'.$_GET['count'].'</cell><cell>'.$_GET['sn'].'</cell></row>';
		while ($row = mysqli_fetch_assoc( $cur )) {
			$color='';
			$class='';
			$bgcolor='';
			$selected='';
			$style='';
			if ($func) {
				if (is_array($args) && count($args)) $ar=array_merge(array($row),$args); else $ar=array($row);
				$uf=call_user_func_array ($func, $ar);
				if (isset($uf['row'])) $row=$uf['row'];
				if ($showTableColors && isset($uf['class'])) {
					if(count($tblStylesTransform)) {
						$stA=array();
						$clA=explode(" ",$uf['class']);
						foreach($clA as $ix=>$cl) if(isset($tblStylesTransform[$cl])) $stA[]=$tblStylesTransform[$cl];
						$stAtxt=implode(";",$stA);
						$uf['style']=isset($uf['style']) && $uf['style']?$uf['style'].";".$stAtxt:$stAtxt;
				} else $class=" class='".$uf['class']."'";
				}
				if (isset($uf['style'])) $style=" style='".$uf['style']."'";
				if (isset($uf['selected'])) $selected=" selected='1'";
			}
			
			$pr="<$trTag id='".($row['id'])."'".$selected.$class.$style.">";
			if (($bodyTag=='rows') && count($cmArr)) {
				$pr.="<userdata name='cm'>";
				$cmpr='';
				foreach($cmArr as $cm) if (strlen($cmpr)) $cmpr.=",".$row[$cm] ; else  $cmpr.=$row[$cm];
				$pr.=$cmpr."</userdata>";
			}
			$i=0;
			foreach($fields as $f) $pr.='<'.$tdTag.' '.(isset($uf['type_'.$f]) ? ' type="'.$uf['type_'.$f].'"' :'').(isset($uf['class_'.$f]) ? ' class="'.$uf['class_'.$f].'"' :'').'>'.(substr($row[$f],0,8)=='<![CDATA'?$row[$f]:htmlspecialchars($row[$f]))."</$tdTag>";			
			//foreach($fields as $f) $pr.="<cell>".htmlspecialchars ($row[$f],ENT_NOQUOTES)."</cell>";			
			$pr.="</$trTag>";
			print($pr);
		}
		print '</'.$bodyTag.'>';
		mysqli_free_result( $cur );
	}
	
	function printJsonRows( $fields,$cmArr='',$func='',$args='') {
	global $posStart;
		if (!($cur = $this->query())) {
			return null;
		}
		$fields=explode(",",$fields);
		$cmArr=($cmArr ? explode(",",$cmArr) : array());
		$response=array();  $response['status']=0;$response['message']='';$response['fields']=$fields;$response['list']=array();
		while ($row = mysqli_fetch_assoc( $cur )) {
			$color='';
			$class='';
			$bgcolor='';
			$selected='';
			$style='';
			$pom=array();
			if ($func) {
				if (is_array($args) && count($args)) $ar=array_merge(array($row),$args); else $ar=array($row);
				$uf=call_user_func_array ($func, $ar);
				if (isset($uf['row'])) $row=$uf['row'];
				if (isset($uf['class'])) $pom['class']=$uf['class'];
				if (isset($uf['style'])) $pom['style']=$uf['style'];
				if (isset($uf['selected'])) $pom['selected']=1;
			}
			
			$pom['id']=$row['id'];
			if (count($cmArr)) {
				$cmpr='';
				foreach($cmArr as $cm) if (strlen($cmpr)) $cmpr.=",".$row[$cm] ; else  $cmpr.=$row[$cm];
				$pom['cm']=$cmpr;
			}
			$i=0;$data=array();
			foreach($fields as $f) $data[]=$row[$f]===null?'':$row[$f];			
			$pom['data']=$data;
			$response['list'][]=$pom;
		}
		echo json_encode($response);
		return true;
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

	function printSimpleTree( $id,$text,$subs='',$cmArr='',$func='',$args='') {
	global $posStart;
		if (!($cur = $this->query())) {
			return null;
		}
		
		$cmArr=($cmArr ? explode(",",$cmArr) : array());
		print '<tree id="'.$id.'">';
		//print '<row id="1"><cell>'.$_GET['posStart'].'</cell><cell>'.$_GET['count'].'</cell><cell>'.$_GET['sn'].'</cell></row>';
		while ($row = mysqli_fetch_assoc( $cur )) {
			$im0='';
			$im1='';
			$im2='';
			$open='';
			$call='';
			$select='';
			$checked='';
			$aCol='';
			$sCol='';
			$style='';
			$imheight='';
			$tooltip='';
			$imwidth='';
			$topoffset='';
			$radio='';

			if ($func) {
				if (is_array($args) && count($args)) $ar=array_merge(array($row),$args); else $ar=array($row);
				$uf=call_user_func_array ($func, $ar);
				if (isset($uf['row'])) $row=$uf['row'];
				if (isset($uf['im0'])) $im0=" im0='".$uf['im0']."'";
				if (isset($uf['im1'])) $im1=" im1='".$uf['im1']."'";
				if (isset($uf['im2'])) $im2=" im2='".$uf['im2']."'";
				if (isset($uf['open'])) $open=" open='1'";
				if (isset($uf['call'])) $call=" call='1'";
				if (isset($uf['select'])) $select=" select='1'";
				if (isset($uf['checked'])) $checked=" checked='1'";
				if (isset($uf['tooltip ']))  $tooltip =" tooltip ='".$uf['tooltip ']."'";
				if (isset($uf['aCol']))  $aCol=" aCol='".$uf['aCol']."'";
				if (isset($uf['sCol']))  $sCol=" sCol='".$uf['sCol']."'";
				if (isset($uf['style']))  $style=" style='".$uf['style']."'";
				if (isset($uf['imheight']))  $imheight=" imheight='".$uf['imheight']."'";
				if (isset($uf['imwidth']))  $imwidth=" imwidth='".$uf['imwidth']."'";
				if (isset($uf['topoffset']))  $topoffset=" topoffset='".$uf['topoffset']."'";
				if (isset($uf['radio']))  $radio=" radio='".$uf['radio']."'";
			}
			$txt=str_replace('"',"'",str_replace("&","&amp;",$row[$text]));
			if ($subs && intval($row[$subs])) $ch="child='1'"; else $ch='';
			$pr="<item $ch text=\"".$txt."\" id='".($row['id'])."'".$im0.$im1.$im2.$open.$call.$select.$checked.$tooltip.$aCol.$sCol.$style.$imheight.$imwidth.$topoffset.$radio.">";
			if (count($cmArr)) {
				$pr.="<userdata name='cm'>";
				$cmpr='';
				foreach($cmArr as $cm) if (strlen($cmpr)) $cmpr.=",".$row[$cm] ; else  $cmpr.=$row[$cm];
				$pr.=$cmpr."</userdata>";
			}					
			$pr.="</item>";
			print($pr);
		}
		print '</tree>';
		mysqli_free_result( $cur );
	}
}







?>
