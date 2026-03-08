<?

$row=new simUser($database);

$row->load($mainFrame->adminId);


simConvertLangConsts($tmpl,"opt_register","_USER_");
simProcessRadio($tmpl,"opt_register", "CKE", $row->emlcontact);
$tmpl->addObject("opt_register", $row, "row_",true);
$tmpl->addVar("opt_register", "SEL".$row->colorset, "SELECTED");
$tmpl->addVar("opt_register", "SELF".$row->mailfreq, "SELECTED");

$mainFrame->addScript("var regfrmmsg='"._USER_REG_ALERT."';","regfrmmsg");
$mainFrame->addScript("var regfrmmsg2='"._USER_REG_ALERT2."';","regfrmmsg2");
$mainFrame->includeScript("opt/register/js/testRegForm.js","testregform");

$mainFrame->setTitle("Profil: ".$row->name);

?>