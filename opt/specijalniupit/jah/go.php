<?

include("opt/specijalniupit/specijalniupit.class.php");


$row=new simSpecijalniUpit($database);

	$row->load($id);
	$res->javascript("new_tab('index.php?opt=".$row->qopt."&act=specijalniupit&upitID=".$row->id."')");

?>