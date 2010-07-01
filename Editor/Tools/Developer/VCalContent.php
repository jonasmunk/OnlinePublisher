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


$parser = new VCalParser();
$cal = $parser->parseURL(requestGetText('url'));
//print_r($cal);
//$serializer = new FeedSerializer();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" variant="Light">'.
'<content>'.
'<headergroup>'.
'<header title="Summary"/>'.
'<header title="Location"/>'.
'<header title="Start date"/>'.
'<header title="End date"/>'.
'<header title="Timestamp"/>'.
'<header title="Recurrence" width="10%"/>'.
'</headergroup>';

foreach($cal->getEvents() as $event) {
	$gui.='<row>'.
	'<cell>'.
	'<icon icon="Template/Generic" help="'.encodeXML($event->getUniqueId()).'"/>'.
	'<text>'.encodeXML($event->getSummary()).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($event->getLocation()).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event->getStartDate())).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event->getEndDate())).'</cell>'.
	'<cell nowrap="true">'.encodeXML(UserInterface::presentLongDateTime($event->getTimeStamp())).'</cell>'.
	'<cell>'.encodeXML(showReccurrenceRules($event->getRecurrenceRules())).'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);

function showReccurrenceRules($rules) {
	$out = '';
	foreach ($rules as $rule) {
		$out.=print_r($rule,true);
	}
	return $out;
}
?>