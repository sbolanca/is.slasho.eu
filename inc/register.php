<?



class simRegistration {
	var $_session=null;
	var $_userstate=null;
	var $_db = null;
	
	function simRegistration(&$db) {
		$this->_db =& $db;
	}
	
	function initSession() {
		global $simConfig_lifetime;
		$session =& $this->_session;
		$session = new simSession( $this->_db );
		$session->purge(intval( $simConfig_lifetime ));

		$sessioncookie = simGetParam( $_COOKIE, 'sessioncookie', null );
		$usercookie = simGetParam( $_COOKIE, 'usercookie', null );
		
		
		if ($session->load( md5( $sessioncookie . $_SERVER['REMOTE_ADDR'] ) )) {
			// Session cookie exists, update time in session table
			$session->time = time();
			$session->update();
		} else {
			$session->generateId();
			$session->guest = 1;
			$session->username = '';
			$session->admin = '0';
			$session->super = '0';
			$session->name = '';
			$session->time = time();
			
			if (!$session->insert()) {
				die( $session->getError() );
			}

			setcookie( "sessioncookie", $session->getCookie(), time() + 43200, "/" );
			//$_COOKIE["sessioncookie"] = $session->getCookie();

			if ($usercookie) {
				// Remember me cookie exists. Login with usercookie info.
				$this->login($usercookie['username'], $usercookie['password']);
			}
		}
	}

	function login( $username=null,$passwd=null ) {
		global $acl;

		$usercookie = simGetParam( $_COOKIE, 'usercookie', '' );
		$sessioncookie = simGetParam( $_COOKIE, 'sessioncookie', '' );
		if (!$username || !$passwd) {
			$username = trim( simGetParam( $_POST, 'username', '' ) );
			$passwd = trim( simGetParam( $_POST, 'passwd', '' ) );
			$passwd = md5( $passwd );
			$bypost = 1;
		}
		$remember = trim( simGetParam( $_POST, 'remember', '' ) );

		if (!$username || !$passwd) {
			echo "<script> alert(\"Login incomplete !\"); window.history.go(-1); </script>\n";
			exit();
		} else {
			$this->_db->setQuery( "SELECT id, username, gid, super,name"
			. "\nFROM user"
			. "\nWHERE username='$username' AND password='$passwd' AND active=1"
			);
			$row = null;
			if ($this->_db->loadObject( $row )) {
				
			
				$session =& $this->_session;
				$session->guest = 0;
				$session->username = $username;
				$session->userID = intval( $row->id );
				$session->super = $row->super;
				$session->admin = $row->admin;
				$session->name = $row->name;
				
				$session->update();
				
				if ($remember=="yes") {
					$lifetime = time() + 365*24*60*60;
					setcookie( "usercookie[username]", $username, $lifetime, "/" );
					setcookie( "usercookie[password]", $passwd, $lifetime, "/" );
				}
				//mosCache::cleanCache('com_content');
				//mosCache::cleanCache();
			} else {
				if (isset($bypost)) {
					echo "<script>alert(\"Login incorrect.\"); window.history.go(-1); </script>\n";
				} else {
					$this->logout();
					simRedirect("index.php");
				}
				exit();
			}
		}
	}
	
	function loginByCode( $id,$passwd,$redirect ) {
		global $acl;

			$bypost = 1; $remember="no";
			$this->_db->setQuery( "SELECT id, username, gid, super,name"
			. "\nFROM user"
			. "\nWHERE id=$id AND password='$passwd' AND active=1"
			);
			$row = null;
			if ($this->_db->loadObject( $row )) {
				
				$username=$row->username;
				$session =& $this->_session;
				$session->guest = 0;
				$session->username = $username;
				$session->userID = intval( $row->id );
				$session->super = $row->super;
				$session->admin = $row->admin;
				$session->name = $row->name;
				
				$session->update();
				
				if ($remember=="yes") {
					$lifetime = time() + 365*24*60*60;
					setcookie( "usercookie[username]", $username, $lifetime, "/" );
					setcookie( "usercookie[password]", $passwd, $lifetime, "/" );
				}
					simRedirect($redirect);
					//mosCache::cleanCache('com_content');
				//mosCache::cleanCache();
			} else {
				if (isset($bypost)) {
					echo "<script>alert(\"Login incorrect.\"); window.history.go(-1); </script>\n";
				} else {
					$this->logout();
					simRedirect($redirect);
				}
				exit();
			}
		
	}
	/**
	* User logout
	*
	* Reverts the current session record back to 'anonymous' parameters
	*/
	function logout($destroy=true) {
		//mosCache::cleanCache('com_content');
		//mosCache::cleanCache();
		$session =& $this->_session;

		$session->guest = 1;
		$session->username = '';
		$session->userID = '';
		$session->name = '';
		$session->admin = 0;
		$session->super = 0;
		
		$session->update();

		// this is daggy??
		$lifetime = time() - 1800;
		setcookie( "usercookie[username]", " ", $lifetime, "/" );
		setcookie( "usercookie[password]", " ", $lifetime, "/" );
		setcookie( "usercookie", " ", $lifetime, "/" );
		if ($destroy) @session_destroy();
	}
	/**
	* @return mosUser A user object with the information from the current session
	*/
	function getUser() {
		$user = new simUser( $this->_db );

		$user->id = intval( $this->_session->userID );
		$user->username = $this->_session->username;
		$user->name = $this->_session->name;
		$user->admin = $this->_session->admin;
		$user->super = $this->_session->super;
	
		return $user;
	}	
}


class simUser  extends simDBTable  {
	var $id=null;
	var $code=null;
	var $name=null;
	var $ime=null;
	var $prezime=null;
	var $username=null;
	var $password=null;
	var $email=null;
	var $telefon=null;
	var $admin=null;
	var $super=null;
	var $aktualno=null;
	var $colorset=null;
	var $mailfreq=null;
	var $active=null;
	var $recyclebin=null;


	/**
	* @param database A database connector object
	*/
	function simUser( &$database ) {
		$this->simDBTable( 'user', 'id', $database );
	}

	function check($isJah=false) {
		
		if (trim( $this->username ) == '') {
			$this->_error = "_REGWARN_UNAME";
			return false;
		}
		if (trim( $this->name ) == '') {
			$this->_error = "_REGWARN_NAME";
			return false;
		}

		if (eregi( "[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", $this->username) || strlen( $this->username ) < 3) {
			$this->_error = sprintf( "_VALID_AZ09", "_PROMPT_UNAME", 2 );
			return false;
		}

		if ((trim($this->email == "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email )==false)) {
			$this->_error = "_REGWARN_MAIL";
			return false;
		}

		// check for existing username
		$this->_db->setQuery( "SELECT id FROM user "
		. "\nWHERE LOWER(username)=LOWER('$this->username') AND id!='$this->id'"
		);

		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->_error = "_REGWARN_INUSE";
			return false;
		}
		/*
		if ($mosConfig_uniquemail) {
			// check for existing email
			$this->_db->setQuery( "SELECT id FROM #__users "
			. "\nWHERE email='$this->email' AND id!='$this->id'"
			);

			$xid = intval( $this->_db->loadResult() );
			if ($xid && $xid != intval( $this->id )) {
				$this->_error = _REGWARN_EMAIL_INUSE;
				return false;
			}
		}
		*/
		return true;
	}

	

	function delete( $oid=null ) {
		global $acl;

		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}
		

		$this->_db->setQuery( "DELETE FROM $this->_tbl WHERE $this->_tbl_key = '".$this->$k."'" );

		if ($this->_db->query()) {
			
			return true;
		} else {
			$this->_error = $this->_db->getErrorMsg();
			return false;
		}
	}
	function generateUsername($x='') {
		$ime=preg_replace('/\W/', '', strtolower(trim(clearFilename($this->ime))));
		$prezime=preg_replace('/\W/', '', strtolower(trim(clearFilename($this->prezime))));
		$this->username=substr($ime,0,1).$prezime;
		if ($x) $this->username.=$x;
		return $this->username;
	}
	function generatePassword($length=8) {
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ";
		$this->password = "";
		for ($i=0;$i <= $length;$i++) $this->password .= $chars{mt_rand(0,strlen($chars))};
		return $this->password;
	}
	function loadByCode($h) {
		$q="SELECT * FROM user WHERE mbg='".$h."'";
		$this->_db->setQuery($q);		
		$this->_db->loadObject($this);
	}
	
	
}

class simSession extends simDBTable {
	var $session_id=null;
	var $time=null;
	var $userID=null;
	var $admin=null;
	var $super=null;
	var $username=null;
	var $name=null;
	var $guest=null;
	var $_session_cookie=null;

	function simSession( &$db ) {
		$this->simDBTable( 'session', 'session_id', $db );
	}

	function insert() {
		$ret = $this->_db->insertObject( $this->_tbl, $this );

		if( !$ret ) {
			$this->_error = strtolower(get_class( $this ))."::store failed <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function update( $updateNulls=false ) {
		$ret = $this->_db->updateObject( $this->_tbl, $this, 'session_id', $updateNulls );

		if( !$ret ) {
			$this->_error = strtolower(get_class( $this ))."::store failed <br />" . $this->_db->stderr();
			return false;
		} else {
			return true;
		}
	}

	function generateId() {
		$failsafe = 20;
		$randnum = 0;
		while ($failsafe--) {
			$randnum = md5( uniqid( microtime(), 1 ) );
			if ($randnum != "") {
				$cryptrandnum = md5( $randnum );
				$this->_db->setQuery( "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key=MD5('$randnum')" );
				if(!$result = $this->_db->query()) {
					die( $this->_db->stderr( true ));
				}
				if ($this->_db->getNumRows($result) == 0) {
					break;
				}
			}
		}
		$this->_session_cookie = $randnum;
		$this->session_id = md5( $randnum . $_SERVER['REMOTE_ADDR'] );
	}

	function getCookie() {
		return $this->_session_cookie;
	}

	function purge( $inc=1800 ) {
		$past = time() - $inc;
		$query = "DELETE FROM $this->_tbl"
		. "\nWHERE (time < $past)";
		$this->_db->setQuery($query);

		return $this->_db->query();
	}
}



?>