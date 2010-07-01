<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Calendar.php';
require_once '../../Classes/Calendarsource.php';
require_once '../../Classes/UserInterface.php';
require_once 'CalendarsController.php';

$calendars = Calendar::search();
$sources = Calendarsource::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<content>'.
'<group title="Kalendere">'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="40%"/>'.
'</headergroup>';
foreach ($calendars as $calendar) {
	$gui.='<row link="Calendar.php?id='.$calendar->getId().'" target="_parent">'.
	'<cell>'.
	'<icon icon="'.$calendar->getIcon().'"/>'.
	'<text>'.In2iGui::escape($calendar->getTitle()).'</text>'.
	'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</group>'.
'<group title="Kilder">'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="40%"/>'.
'</headergroup>';
foreach ($sources as $source) {
	$gui.='<row link="Source.php?id='.$source->getId().'" target="_parent">'.
	'<cell>'.
	'<icon icon="'.$source->getIcon().'"/>'.
	'<text>'.In2iGui::escape($source->getTitle()).'</text>'.
	'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</group>'.
'</content>'.
'</result>'.
'</interface>'.
'</xmlwebgui>';

In2iGui::display(array("List","Result","Script"),$gui);
?>