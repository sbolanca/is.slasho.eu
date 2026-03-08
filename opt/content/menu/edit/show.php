<?

if (!$row->dbindex) $row->dbindex=0;

$query="SELECT c.id, IF (c.menu<>'' AND c.menu IS NOT NULL,CONCAT(SUBSTRING(c.title,1,40),' [',SUBSTRING(c.menu,1,30),']'),SUBSTRING(c.title,1,70)) as title, IF(c.id=".$row->dbindex.",'selected','') as selected FROM content as c "
."\n ORDER BY c.title";
$database->setQuery($query);
$contents=$database->loadObjectList();

	$tmpl->readTemplatesFromInput( "opt/content/menu/".$row->type.".html");
	$tmpl->addObject("opt_content_menu_item", $contents, "it_",true);
	$menuspecific= $tmpl->getParsedTemplate("opt_content_menu");
	$tmpl->freeTemplate( "opt_content_menu", true );


?>