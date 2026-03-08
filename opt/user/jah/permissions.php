<?
$admin=intval(simGetParam($_REQUEST,'admin',0));

$ids=str_replace("|",",",trim(simGetParam($_REQUEST,'ids',$id)));

$database->execQuery("UPDATE user SET admin=$admin WHERE id IN (".$ids.")");

$database->setQuery("SELECT id,name FROM user WHERE id IN (".$ids.")");
$rows=$database->loadObjectList('id');

$ispis=$admin ? 'x' : '';
$ispislog=$admin ? 'ADMIN' : 'GOST';
foreach($rows as $ix=>$row) {
	$res->changeCellValueByCode("tbl_user",$ix,'admin',$ispis);
	$LOG->savelogNOW("Promjena ovlasti korisnika",$row->name." -> ".$ispislog,'',$ix);
}
$res->alert("Ovlasti korisnika su promjenjene u $ispislog.");

?>