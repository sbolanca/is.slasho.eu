<?

#Provjera da li huzip šifre postoje u bazi
	
$database->setQuery("SELECT m.* FROM `medjunarodni_claim_import` AS m
LEFT JOIN snimka AS s ON m.huzip=s.huzip AND s.recyclebin=0
WHERE s.id IS NULL");
$rows=$database->loadObjectList();

#Provjera da li sudjelovanjeID postoji u bazi
$database->setQuery("SELECT m.* FROM `medjunarodni_claim_import` AS m
LEFT JOIN sudjelovanje AS s ON m.sudjelovanjeID=s.id AND s.recyclebin=0
WHERE m.sudjelovanjeID>0 AND s.id IS NULL");
$rows=$database->loadObjectList();


#Provjera postojećih sudjelovanja da li su iste vrsteID
$database->setQuery("SELECT m.*,s.vrstaID AS stara_vrstaID FROM `medjunarodni_claim_import` AS m
LEFT JOIN sudjelovanje AS s ON m.sudjelovanjeID=s.id
WHERE m.status='Već postoji u bazi' AND m.vrstaID<>s.vrstaID AND s.recyclebin=0");
$rows=$database->loadObjectList();	
		
		
		

?>