<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Calendar.php';
require_once 'CalendarsController.php';

$calendarId = requestGetNumber('calendar');
$calendars = Calendar::search();

$close = CalendarsController::getBaseWindow();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Ny begivenhed" icon="Basic/Time">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateEvent.php" method="post" name="Formula" focus="title" submit="true">'.
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
'<group size="Large">'.
'<textfield badge="Titel:" name="title" object="Title"/>'.
'<textfield badge="Lokation:" name="location"/>'.
'<textfield badge="Beskrivelse:" lines="8" name="note"/>'.
'<datetime badge="Startdato:" name="startdate" object="StartDate" value="'.In2iGui::toDateTime(mktime()).'" display="dmy"/>'.
'<datetime badge="Slutdato:" name="enddate" object="EndDate" value="'.In2iGui::toDateTime(mktime()+(60*60)).'" display="dmy"/>'.
'<select badge="Kalendere:" lines="5" name="calendars[]" multiple="true">'.
In2iGui::buildOptions($calendars,array($calendarId)).
'</select>'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>