<?



$q="SELECT c.id,c.naziv as value,c.code "
." FROM stavka as c "
." WHERE (c.naziv LIKE '$term%' OR c.naziv LIKE '% $term%' OR c.code LIKE '$term%')";

$database->setQuery($q);
$rows=$database->loadObjectList();

echo json_encode($rows); 

?>