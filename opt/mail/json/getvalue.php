<?



$q="SELECT c.id,c.subject as code,c.title as value "
." FROM mail as c "
." WHERE (c.title LIKE '$term%' OR c.title LIKE '% $term%')";

$database->setQuery($q);
$rows=$database->loadObjectList();

echo json_encode($rows); 

?>