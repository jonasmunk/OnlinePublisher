<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigt"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" source="Hierarchy.php"/>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame");
writeGui($xwg_skin,$elements,$gui);
?>