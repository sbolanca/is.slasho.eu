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

$loginFormAction = $_SERVER['PHP_SELF'];

$error='';

if(isset($_FILES['upload']) && is_array($_FILES['upload']) && is_file($_FILES['upload']['tmp_name'])) {
	$info=pathinfo($_FILES['upload']['name']);
	if(!(strtolower($info['extension'])=='csv')) $error='Dozvoljeno je uploadati samo CSV datoteke!';
	else {
		$filename=date("YmdHis").".csv";
		move_uploaded_file($_FILES['upload']['tmp_name'], $filename);
		if (($fp = fopen($filename, "r")) !== FALSE) {
			while (($data = fgetcsv($fp)) !== FALSE) $content[]=$data;
			fclose($fp);
			
			$newContent=array();
			foreach($content as $i=>$row) {
				$new=$row;
				if(count($row)>12)  $new[12]=$row[13];
				if(count($row)>17)  $new[17]=0;
				$new[count($row)-1]=trim($new[count($row)-1]);
				$newContent[]=$new;
			}

			$fp = fopen($filename, 'w');

			foreach ($newContent as $fields) {
				fputs($fp,'"'.implode('","',$fields).'"'."\r\n");
			}

			fclose($fp);
			
			define('CHUNK_SIZE', 1024*1024); 
			$mimetype = 'mime/type';
			header('Content-Type: application/force-download' );
			header('Content-Disposition: attachment; filename="'.$info['filename'].'.converted.'.$info['extension'].'"');

			readfile_chunked($filename);
			unlink($filename);
			die();
			//$error='Datoteka '.$info['basename'].' je konvertirana.';
			
		}
	}
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CSV DancingBear konverter</title>
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
    width: 700px;
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
	width: 250px;
	height: 200px;
	background-color: #01081B;
	float: left;
	background-image:url("images/blue2.jpg");
	font-family: Arial;
	font-size: 25px;
	font-weight: bold;
	color: white;
}

div#okvir_desno {
	width: 450px;
	height: 200px;
	background-color: #FFFFFF;
	float: left;
	font-family: Tahoma;
}
</style>
<?php if ($error) echo "<script> alert('$error');</script>"; ?>
</head>



<body OnLoad="document.prijava.login.focus();">


	<div id="okvir">
		<div id="okvir_lijevo">
			<div style="padding:10px 0px 0px 20px;">
				DancingBear<br>CSV<BR>Converter
			</div>
		</div>
		<div id="okvir_desno">
			<div style="margin: 20px 0px 0px 20px;">
				<form name="prijava" method="post" action="<?php echo $loginFormAction; ?>"  enctype="multipart/form-data" >
					<div style="font-weight:bold; margin-bottom:10px;">Upload CSV datoteke</div>
					<label for="upload" style="font-size:12px;">CSV:</label>
					<input type="file" id="upload" name="upload"  /><br />
					<input type="submit" value="Pošalji i konvertiraj" style="height:25px; margin-top:20px;font-size:14px;" />
				</form>
			</div>
		</div>
	</div>


</body>



</html>
