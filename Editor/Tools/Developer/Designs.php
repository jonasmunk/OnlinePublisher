<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center">'.
'<titlebar title="Designs" icon="File/css"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="DesignsList.php"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame");
writeGui($xwg_skin,$elements,$gui);
?>