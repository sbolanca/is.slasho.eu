<?php

require_once( 'configuration.php' );

//require_once 'inc/mobile/Mobile_Detect.php';
//$detect = new Mobile_Detect;



if(preg_match('/(?i)msie [1-9]/',$_SERVER['HTTP_USER_AGENT'])) $forbiden=true;
else $forbiden=false;

//$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

$Connection = mysqli_connect($simConfig_host, $simConfig_user, $simConfig_password,$simConfig_db) or die();
//mysqli_select_db($simConfig_db, $Connection);
// *** Validate request to login to this site.
session_start();



$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($accesscheck)) {
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
}

if (isset($_POST['login'])) {
	
	/*require_once 'inc/zahtjev.php';
	$zahtjev = zahtjev::detect(); 
	
	$details="preglednik: ".$zahtjev['preglednik']
			."\nverzija: ".$zahtjev['verzija']
			."\nplatforma: ".$zahtjev['platforma']
			."\nip_adresa: ".$zahtjev['ip_adresa'] 
			."\nprovider: ".$zahtjev['provider'] 
			."\ngrad: ".$zahtjev['grad'] 
			."\ndrzava: ".$zahtjev['drzava'] 
			."\nuseragent: ".$zahtjev['useragent'];
	*/
	$details='';
  $loginUsername=$_POST['login'];
  
  $password=md5($_POST['password']);
   $passwordo=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  
  $LoginRS__query=sprintf("SELECT username, aktualno,colorset, password, admin, super, name, id, email FROM user WHERE username='%s' AND (password='%s' OR password='%s') AND password<>'' AND password IS NOT NULL AND active=1",
     addslashes($loginUsername), addslashes($password), addslashes($passwordo)); 
   		 @mysqli_query($Connection,"SET NAMES 'utf8'");
         @mysqli_query($Connection,"SET CHARCTER SET utf8");
         @mysqli_query($Connection,"SET COLLATION_CONNECTION='utf8_croatian_ci'");
  $LoginRS = mysqli_query($Connection,$LoginRS__query) or die(mysqli_error($Connection));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $MM_lrow=mysqli_fetch_assoc($LoginRS);	

	//if ($MM_lrow['password']==$passwordo) @mysqli_query("UPDATE user SET password='".(get_magic_quotes_gpc() ? $password : addslashes($password))."' WHERE id=".$MM_lrow['id'], $Connection);
	
	

    //register the session variables
	$_SESSION['MM_Username'] = $loginUsername;
	$_SESSION['MM_id'] = $MM_lrow['id'];	
	$_SESSION['MM_email'] =$MM_lrow['email'];	
	$_SESSION['MM_name'] = $MM_lrow['name'];	
	$_SESSION['MM_admin'] = $MM_lrow['admin'];	
	$_SESSION['MM_super'] = $MM_lrow['super'];	
	$_SESSION['aktualno'] = intval($MM_lrow['aktualno']);	
	$_SESSION['colorset'] = $MM_lrow['colorset'];	
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
	//$_SESSION['deviceType'] = $deviceType;	
	
	
	
	
	@mysqli_query($Connection,"INSERT INTO log (importance,app,userID,title,subject,opt,act,user,ip,details) VALUES"
		 ."\n (1,'is',".intval($MM_lrow['id']).",'Login','".$MM_lrow['name']." [".$loginUsername."]','login','ok','".$MM_lrow['name']."','".$_SERVER['REMOTE_ADDR']."','$details')");
	$loginID=mysqli_insert_id( $Connection);
	$GLOBALS['MM_loginID'] = $loginID;	
	$_SESSION['MM_loginID'] = $loginID;	
	
    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
  @mysqli_query( $Connection,"INSERT INTO log (importance,app,title,subject,opt,act,ip,details) VALUES"
		 ."\n (1,'is','NEUSPJELI LOGIN','username=".$loginUsername." , password=".$_POST['password']."','login','failed','".$_SERVER['REMOTE_ADDR']."','$details')");

    header("Location: ". $MM_redirectLoginFailed );
  }
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><? echo $simConfig_sitename; ?> - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
	background-color: #5A5A5A;
}

div {
	margin: 0px;
	padding: 0px;
}

div#okvir {
    width: 574px;
    height: 200px;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    position: absolute;
    margin: auto;
	box-shadow: 5px 5px 10px #01081A;
}

div#okvir_lijevo {
	width: 324px;
	height: 200px;
	background-color: #01081B;
	float: left;
	background-image:url("images/logo10.png");
	background
	font-family: Arial;
	font-size: 25px;
	font-weight: bold;
	color: white;
}


div#okvir_desno {
	width: 250px;
	height: 200px;
	background-color: #FFFFFF;
	float: left;
	font-family: Tahoma;
}
</style>

</head>



<body OnLoad="document.prijava.login.focus();">


	<div id="okvir">
		<div id="okvir_lijevo">
			<div style="padding:10px 0px 0px 20px;">
				
			</div>
		</div>
		<div id="okvir_desno">
			<div style="margin: 20px 0px 0px 20px;">
				<form name="prijava" method="post" action="<?php echo $loginFormAction; ?>">
					<div style="font-weight:bold; margin-bottom:10px;">PRIJAVA</div>
					<label for="login" style="font-size:11px;">Korisničko ime:</label><br />
					<input type="text" id="login" name="login" style="width:195px; font-size:12px; margin:3px 0px;" /><br />
					<label for="password" style="font-size:11px;">Lozinka:</label><br />
					<input type="password" id="password" name="password" style="width:195px; font-size:12px; margin-bottom:10px;" /><br />
					<input type="submit" value="Prijava" style="width:60px; height:25px; font-size:12px;" />
				</form>
			</div>
		</div>
	</div>


</body>



</html>