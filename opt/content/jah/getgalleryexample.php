<?
include_once("opt/content/content.class.php");

	$query="SELECT image FROM gallery_item"
	."\n WHERE galleryID=".$id
	."\n ORDER BY ordering";
	$database->setQuery($query);
	$items=$database->loadObjectList();
	
	$cont='';
	foreach($items as $item) $cont.='<div><img src="files/Image/'.$item->image.'" width="100" style="margin:1px;"/></div>';
	
	$act=new jahAction('change','_galleryexample');
	$act->addBlock($cont);
	
	$res->addAction($act);

?>