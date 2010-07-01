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
require_once '../../Classes/Calendarsource.php';
require_once 'CalendarsController.php';

$id = requestGetNumber('id');
if ($id>0) {
	CalendarsController::setSourceId($id);
	CalendarsController::setSelection('source-'.$id);
}
$id = CalendarsController::getSourceId();
$source = Calendarsource::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML(shortenString($source->getTitle(),30)).'" icon="Basic/Internet">'.
'<close link="Overview.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Egenskaber" icon="Tool/Calendar" overlay="Info" link="SourceProperties.php"/>'.
'<tool title="Synkronisér" icon="Basic/Refresh" link="SourceList.php?id='.$id.'&amp;force=true" target="List"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="SourceList.php?id='.$id.'" name="List" object="List"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>