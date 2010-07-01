<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendar
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/Calendar.php';
require_once '../../Classes/Event.php';
require_once 'CalendarsController.php';

$id = requestGetNumber('id');
if ($id>0) {
	CalendarsController::setCalendarId($id);
	CalendarsController::setSelection('calendar-'.$id);
}
$id = CalendarsController::getCalendarId();
$calendar = Calendar::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML(shortenString($calendar->getTitle(),30)).'" icon="Tool/Calendar">'.
'<close link="Overview.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Egenskaber" icon="Tool/Calendar" overlay="Info" link="CalendarProperties.php"/>'.
'<tool title="Ny begivenhed" icon="'.Event::getIcon().'" overlay="New" link="NewEvent.php?calendar='.$id.'"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="EventList.php?id='.$id.'" name="List" object="List"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>