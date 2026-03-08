<?

$row=new simUser($database);
$row->emlcontact=1;

simConvertLangConsts($tmpl,"opt_register","_USER_");
simProcessRadio($tmpl,"opt_register", "CKE", $row->emlcontact);
$tmpl->addObject("opt_register", $row, "row_",true);

$mainFrame->addScript("var regfrmmsg='"._USER_REG_ALERT."';","regfrmmsg");
$mainFrame->addScript("var regfrmmsg2='"._USER_REG_ALERT2."';","regfrmmsg2");
$mainFrame->includeScript("opt/register/js/testRegForm.js","testregform");



?>