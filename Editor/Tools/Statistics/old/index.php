<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="250">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="0">'.
'<row><cell height="80%">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigter"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" source="Hierarchy.php" name="Left"/>'.
'</content>'.
'</area>'.
'</cell></row><row><cell height="20%" top="10">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Indstillinger"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" source="Settings.php" name="Left"/>'.
'</content>'.
'</area>'.
'</cell>'.
'</row>'.
'</layout>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="Visitors.php" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Frame","Area");
//$elements = array("Window","Toolbar","Frame","Script","Form");
writeGui($xwg_skin,$elements,$gui);

?>