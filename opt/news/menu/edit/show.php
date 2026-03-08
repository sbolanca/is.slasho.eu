<?
if (!$row->dbindex) $row->dbindex=0;

$selgr=$row->dbindex;

include_once("opt/news/news.class.php");

$tmpl->readTemplatesFromInput( "opt/news/menu/".$row->type.".html");

switch ($row->mact) {
		case 'list':	$var='SELL'; break;
		case 'blog':	$var='SELB'; break;
		case 'alist':	$var='SELA'; break;
		default: $var='SELS';
			$rown=new simNews($database);
			$rown->load($row->dbindex);
			if ($rown->groupID) $selgr=$rown->groupID;
			
			$query="SELECT c.id,c.published, c.title, IF(c.id=".$rown->id.",'selected','') as selected FROM news as c "
			."\n WHERE c.groupID=$selgr"
			."\n ORDER BY c.title";
			
			$database->setQuery($query);
			$items=$database->loadObjectList(); 
			$creatnewArr=array(makeOption( '', '',$rown->id,'selected',0,'[--novi članak--]') );
			
			$tmpl->addObject("opt_news_menu_nitem", array_merge($creatnewArr,$items), "itn_",true);
			$tmpl->addVar("opt_news_menu",'selindex',$rown->id);
	}

$query="SELECT c.id,c.published, c.title, IF(c.id=".$selgr.",'selected','') as selected FROM groups as c "
."\n ORDER BY c.title";

$database->setQuery($query);
$itemsGR=$database->loadObjectList();


	
	
	$tmpl->addObject("opt_news_menu_item", $itemsGR, "it_",true);
	$tmpl->addVar("opt_news_menu",$var,'checked');
	$menuspecific= $tmpl->getParsedTemplate("opt_news_menu");
	$tmpl->freeTemplate( "opt_news_menu", true );


?>