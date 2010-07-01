<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Event.php';
require_once '../../Classes/UserInterface.php';
require_once 'CalendarsController.php';

$calendarId = CalendarsController::getCalendarId();
if (requestGetExists('timeSpan')) {
	CalendarsController::setListTimespan(requestGetText('timeSpan'));
}
$timeSpan = CalendarsController::getListTimespan();

$query = array('calendarId'=>$calendarId);
$dates = CalendarsController::getListTimespanDates();
$query['startDate'] = $dates['startDate'];
$query['endDate'] = $dates['endDate'];

$events = Event::search($query);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Gruppér efter">'.
'<selection value="" object="Grouping">'.
'<item title="Intet" value="none"/>'.
'<item title="Dato" value="date"/>'.
'</selection>'.
'</block>'.
'<block title="Tid">'.
'<selection value="'.$timeSpan.'" object="timeSpan">'.
'<item title="Altid" value="always"/>'.
'<item title="Denne uge" value="thisWeek"/>'.
'<item title="Denne måned" value="thisMonth"/>'.
'<item title="Dette år" value="thisYear"/>'.
'</selection>'.
'</block>'.
'</sidebar>'.
'<content>'.
'<group title="Begivenheder">'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="40%"/>'.
'<header title="Start" width="30%"/>'.
'<header title="Slut" width="30%"/>'.
'</headergroup>';
foreach ($events as $event) {
	$gui.='<row link="EventProperties.php?id='.$event->getId().'" target="_parent">'.
	'<cell>'.
	'<icon icon="'.$event->getIcon().'"/>'.
	'<text>'.encodeXML($event->getTitle()).'</text>'.
	'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event->getStartDate())).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event->getEndDate())).'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</group>'.
'</content>'.
'</result>'.
'<script xmlns="uri:Script">
var sideBarDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "EventList.php?timeSpan="+timeSpan.getValue();
	}
}
timeSpan.setDelegate(sideBarDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List","Result","Script");
writeGui($xwg_skin,$elements,$gui);
?>