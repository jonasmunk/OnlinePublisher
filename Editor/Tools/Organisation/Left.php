<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<content padding="3">'.
'<tiles xmlns="uri:Tile" height="auto" width="100%">'.
'<tile title="Oversigt"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Hierarchy.php"/>'.
'</content>'.
'</tiles>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame","Tile");
writeGui($xwg_skin,$elements,$gui);
?>