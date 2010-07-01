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
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="250">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<content padding="3">'.
'<tiles xmlns="uri:Tile" height="auto" width="100%">'.
'<tile title="Udvikler"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Hierarchy.php"/>'.
'</content>'.
'</tiles>'.
'</content>'.
'</area>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="PhpInfo.php" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Frame","Area","Tile");
writeGui($xwg_skin,$elements,$gui);
?>