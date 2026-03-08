<?

include("opt/specijalniupit/specijalniupit.class.php");

$mode=trim(simGetParam($_REQUEST,'mode','edit'));

$row=new simSpecijalniUpit($database);


if(!($mode=='new')) {
	$row->load($id);
	$row->htmlspecialchars();

} else $row->veza="q.id=s.id";

$dir=$simConfig_absolute_path."/opt";
$opts=array();			
			$d = dir($dir);
			while (false !== ($entry = $d->read())) {
				if(is_dir($dir.'/'.$entry) && substr($entry,0,1) != '.') {
					$opts[]=$entry==$row->qopt?array('opt'=>$entry,'sel'=>'selected'):array('opt'=>$entry);
				}
			}
			$d->close();


	$tmpl->readTemplatesFromInput( "opt/specijalniupit/jah/edit.html");
	$tmpl->addObject("opt_specijalniupit", $row, "row_",true);
	$tmpl->addRows("opt_su_o", $opts,'row_');
	//$tmpl->addVar("opt_specijalniupit", 'SELQOPT'.$row->qopt, "SELECTED");
	
	
	
	$cont= $tmpl->getParsedTemplate("opt_specijalniupit");
	
	
	$res->openSimpleDialog((($mode=='new')?"Novi specijalni upit":"Specijalni upit"),$cont,500,1,'white');


?>