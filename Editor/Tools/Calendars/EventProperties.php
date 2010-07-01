<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Event.php';
require_once '../../Classes/Calendar.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/In2iGui.php';
require_once 'CalendarsController.php';

$id = requestGetNumber('id');
$close = CalendarsController::getBaseWindow();

$event = Event::load($id);
$calendarIds = $event->getCalendarIds();
$calendars = Calendar::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="20">'.
'<titlebar title="'.In2iGui::escape(shortenString($event->getTitle(),30)).'" icon="'.$event->getIcon().'">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="10" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateEvent.php" method="post" name="Formula" focus="title" submit="true">'.
'<validation>
if (Title.isEmpty()) {
	Title.setError("Skal udfyldes!");
	Title.blinkError(1000);
	Title.focus();
	return false;
}
else {
	Title.setError("");
}
if (parseInt(StartDate.getValue()) &gt;= parseInt(EndDate.getValue())) {
	EndDate.setError("Slutdatoen skal være større end startdatoen!");
	EndDate.blinkError(1000);
	return false;
}
else {
	EndDate.setError("");
}
return true;
</validation>'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title" object="Title">'.In2iGui::escape($event->getTitle()).'</textfield>'.
'<textfield badge="Location:" name="location">'.In2iGui::escape($event->getLocation()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="note" lines="8">'.In2iGui::escape($event->getNote()).'</textfield>'.
'<datetime badge="Start dato:" name="startdate" object="StartDate" value="'.In2iGui::toDateTime($event->getStartdate()).'" display="dmy"/>'.
'<datetime badge="Slut dato:" name="enddate" object="EndDate" value="'.In2iGui::toDateTime($event->getEnddate()).'" display="dmy"/>'.
'<space/>'.
'<select badge="Kalendere:" lines="5" name="calendars[]" multiple="true">'.
In2iGui::buildOptions($calendars,$calendarIds).
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteEvent.php?id='.$id.'"/>'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Layout","Area","Html");
writeGui($xwg_skin,$elements,$gui);
?>