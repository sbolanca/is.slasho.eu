<?php

$basePath = dirname( __FILE__ );
$path = $basePath . '/../../configuration.php';
require( $path );


mysql_connect($simConfig_host,$simConfig_user,$simConfig_password);
mysql_select_db($simConfig_db );


$query = "SELECT name,email,phone,country, IF(emlcontact>0,'email','phone') as contact FROM user";

$resultSet = mysql_query($query) or die('Can not execute query');


header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=userlist.xls");
?> 
<html>
<head>
<title>Excel Spreadsheet</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<table width="200" border="1" cellspacing="0" cellpadding="2">
  <tr align="center" bgcolor="#FFFF00">
    <td >
      <font face="Arial, Helvetica, sans-serif"><strong>Name
        </strong> </font>    
    </td>
    <td >
      <font face="Arial, Helvetica, sans-serif"><strong>Email
        </strong> </font>    
    </td>
   <td >
      <font face="Arial, Helvetica, sans-serif"><strong>Phone
        </strong> </font>    
    </td>
    <td >
      <font face="Arial, Helvetica, sans-serif"><strong>Country
        </strong> </font>    
    </td>
	   <td >
      <font face="Arial, Helvetica, sans-serif"><strong>Use contact
        </strong> </font>    
    </td>
  </tr>
  <?   if(mysql_num_rows($resultSet)) 
  
  while($row = mysql_fetch_array($resultSet)) { ?>
   <tr >
    <td >
      <? echo $row['name']; ?>
         
    </td>
	    <td >
      <? echo $row['email']; ?>
      
    </td>
	    <td >
     <? echo $row['phone']; ?>
         
    </td>
	    <td >
      <? echo $row['country']; ?>
       
    </td>
	    <td >
      <? echo $row['contact']; ?>
      
    </td>
	 </tr>
	 <? } ?>
  
</table>
</body>
</html>
