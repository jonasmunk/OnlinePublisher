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
$feed = $parser->parseURL(requestGetText('url'));
$serializer = new FeedSerializer();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'<header title="Beskrivelse"/>'.
'<header title="Dato"/>'.
'</headergroup>';

foreach($feed->getItems() as $item) {
	$gui.='<row>'.
	'<cell>'.
	'<icon icon="Template/Generic"/>'.
	'<text>'.encodeXML($item->getTitle()).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($item->getDescription()).'</cell>'.
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