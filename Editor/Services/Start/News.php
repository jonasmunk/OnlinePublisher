<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Feed.php';
require_once '../../Classes/UserInterface.php';


$parser = new FeedParser();
$feed = $parser->parseURL('http://www.in2isoft.dk/services/news/rss/?group=373');
$serializer = new FeedSerializer();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Nyhed"/>'.
'<header title="Dato"/>'.
'</headergroup>';

$items = array_reverse($feed->getItems());

foreach($items as $item) {
	$gui.='<row link="'.$item->getLink().'" target="_blank">'.
	'<cell>'.
	'<icon icon="Template/Generic"/>'.
	'<text><strong>'.encodeXML($item->getTitle()).'</strong><break/>'.encodeXML(html_entity_decode($item->getDescription())).'</text>'.
	'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentDateTime($item->getPubDate())).'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>