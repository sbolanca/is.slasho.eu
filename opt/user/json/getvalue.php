<?



$q="SELECT c.id,c.name as value,c.gi "
." FROM user as c "
." WHERE (c.name LIKE '$term%' OR c.name LIKE '% $term%')";

$database->setQuery($q);
$rows=$database->loadObjectList();

echo json_encode($rows); 

?>