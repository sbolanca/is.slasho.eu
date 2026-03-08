<?

$group=intval(simGetParam($_REQUEST,'var0',0));
$sel=intval(simGetParam($_REQUEST,'sel',0));

$query="SELECT c.*, c.title, IF(c.id=".$sel.",'selected=\"selected\"','') as selected FROM news as c "
."\n WHERE c.groupID=$group"
."\n ORDER BY c.title";

$database->setQuery($query);
$options=$database->loadObjectList();

	$opts='<option value="0">[--novi članak--]</option>';
	
	$tmpl->readTemplatesFromInput( "opt/news/menu/show.html");
	$tmpl->addObject("opt_news_menu_nitem", $options, "itn_",true);
	$opts.= $tmpl->getParsedTemplate("opt_news_menu_nitem");
	$tmpl->freeTemplate( "opt_news_menu_nitem", true );

	$actt=new jahAction('changeselectbox','newsitemsselectbox');
	$actt->addBlock($opts);
	
	$res->addAction($actt);
?>