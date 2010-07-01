<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$path = requestGetText('path');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center">'.
'<titlebar title="'.$path.'" icon="Basic/Start"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" source="../../Tests/Custom.php?path='.$path.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame");
writeGui($xwg_skin,$elements,$gui);
?>