<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Design.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Unikt navn"/>'.
'<header title="Navn"/>'.
'<header title="Beskrivelse"/>'.
'<header title="Valid" align="center"/>'.
'<header title="Kompatibel" align="center"/>'.
'</headergroup>';

$designs = Design::getAvailableDesigns();
foreach($designs as $design) {
	$info = Design::getDesignInfo($design);
	$gui.='<row>'.
	'<cell>'.
	'<icon size="1" icon="File/css"/>'.
	'<text>'.encodeXML($design).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($info['name']).'</cell>'.
	'<cell>'.encodeXML($info['description']).'</cell>'.
	'<cell>'.'<status type="Stopped"/>'.'</cell>'.
	'<cell>'.'<status type="Finished"/>'.'</cell>'.
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