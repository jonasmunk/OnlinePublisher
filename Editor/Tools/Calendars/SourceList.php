<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/VCal.php';
require_once '../../Classes/UserInterface.php';
require_once '../../Classes/Calendarsource.php';
require_once 'CalendarsController.php';

$force = requestGetBoolean('force');
$id = requestGetNumber('id');
$source = Calendarsource::load($id);

$source->synchronize($force);

if (requestGetExists('timeSpan')) {
	CalendarsController::setListTimespan(requestGetText('timeSpan'));
}
$timeSpan = CalendarsController::getListTimespan();


$query = array('sort' => 'startDate');
$dates = CalendarsController::getListTimespanDates();
$query['startDate'] = $dates['startDate'];
$query['endDate'] = $dates['endDate'];

//exit;
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
'<group title="'.UserInterface::presentLongDateTime($query['startDate']).' / '.UserInterface::presentLongDateTime($query['endDate']).'">'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'<header title="Lokation"/>'.
'<header title="Start"/>'.
'<header title="Slut"/>'.
'<header title=""/>'.
'</headergroup>';

$events = $source->getEvents($query);

foreach ($events as $event) {
	$gui.='<row>'.
	'<cell>'.
	'<icon icon="Template/Generic"/>'.
	'<text>'.encodeXMLBreak($event['summary'],'<break/>').'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($event['location']).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event['startDate'])).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event['endDate'])).'</cell>'.
	'<cell nowrap="true">'.($event['recurring'] ? '<icon icon="Basic/Refresh"/>' : '').'</cell>'.
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
		document.location = "SourceList.php?id='.$id.'&amp;timeSpan="+timeSpan.getValue();
	}
}
timeSpan.setDelegate(sideBarDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List","Result","Script");
writeGui($xwg_skin,$elements,$gui);

function showReccurrenceRules($rules) {
	$out = '';
	foreach ($rules as $rule) {
		$out.=print_r($rule,true);
	}
	return $out;
}
?>