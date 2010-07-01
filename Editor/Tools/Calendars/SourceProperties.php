<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Calendarsource.php';
require_once 'CalendarsController.php';

$id=CalendarsController::getSourceId();

$source = Calendarsource::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<sheet width="350" object="ConfirmDelete">'.
'<message xmlns="uri:Message" icon="Caution">'.
'<title>Er du sikker på at du vil slette kalenderkilden?</title>'.
'<buttongroup size="Small">'.
'<button title="Annuller" link="javascript:ConfirmDelete.hide();"/>'.
'<button title="Slet" link="DeleteSource.php?id='.$id.'" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</sheet>'.
'<titlebar title="'.encodeXML(shortenString($source->getTitle(),30)).'" icon="Basic/Internet">'.
'<close link="Source.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="UpdateSource.php" method="post" name="Formula" focus="title" submit="true">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.encodeXML($source->getTitle()).'</textfield>'.
'<textfield badge="Titel (visning):" name="displayTitle">'.encodeXML($source->getDisplayTitle()).'</textfield>'.
'<textfield badge="Adresse:" name="url">'.encodeXML($source->getUrl()).'</textfield>'.
'<textfield badge="Filter:" name="filter">'.encodeXML($source->getFilter()).'</textfield>'.
'<number badge="Interval:" name="syncInterval" value="'.$source->getSyncInterval().'" min="0"/>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="javascript: ConfirmDelete.show();"/>'.
'<button title="Annuller" link="Source.php"/>'.
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