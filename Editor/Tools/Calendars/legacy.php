<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'CalendarsController.php';

$base = CalendarsController::getBaseWindow();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row><cell width="220">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Oversigt"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Selection.php"/>'.
'</content>'.
'<bottom>'.
'<group xmlns="uri:Icon" size="1" spacing="5" titles="right">'.
'<row>'.
'<icon title="Ny kalender" icon="Tool/Calendar" overlay="New" link="NewCalendar.php" target="Right"/>'.
'<icon title="Ny kilde" icon="Basic/Internet" overlay="New" link="NewSource.php" target="Right"/>'.
'</row>'.
'</group>'.
'</bottom>'.
'</area>'.
'</cell><cell>'.
'<iframe xmlns="uri:Frame" source="'.$base.'" name="Right"/>'.
'</cell></row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Frame","Area","Icon");
writeGui($xwg_skin,$elements,$gui);
?>