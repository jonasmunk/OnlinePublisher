<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Feedback
*/
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Request.php';

$feedbackSent = Request::getBoolean('feedbackSent');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="20">';
if ($feedbackSent) {
	$gui.='<sheet width="350" object="SentSheet" visible="true">'.
	'<message xmlns="uri:Message" icon="Message">'.
	'<title>Beskeden er sendt</title>'.
	'<description>Din feedback er sendt til In2iSoft og vi vil melde tilbage når vi har modtaget den.</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="javascript: SentSheet.hide(); document.forms[0].message.focus();" style="Hilited" object="OK"/>'.
	'</buttongroup>'.
	'</message>'.
	'</sheet>';
}
$gui.=
'<titlebar title="Feedback" icon="Tool/Mail">'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Send.php" method="post" name="Formula"'.($feedbackSent ? '' :' focus="message"').'>'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Besked:" name="message" lines="6"/>'.
'<buttongroup size="Large">'.
'<button title="Afsend" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message","Form");
writeGui($xwg_skin,$elements,$gui);
?>