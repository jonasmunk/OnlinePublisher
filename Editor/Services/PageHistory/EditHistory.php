<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';

require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/UserInterface.php';

$id = requestGetNumber('id');
$sql = "select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message from page_history where id=".$id;
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="'.encodeXML(UserInterface::presentDateTime($row['time'])).'" icon="Basic/Time">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateHistory.php" method="post" name="Formula" focus="message">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Besked:" name="message" lines="6">'.encodeXML($row['message']).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteHistory.php?id='.$id.'"/>'.
'<button title="Annuller" link="index.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form","Message");
writeGui($xwg_skin,$elements,$gui);
?>