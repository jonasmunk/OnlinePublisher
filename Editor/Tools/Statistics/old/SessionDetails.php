<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';

$id = requestGetText('id');

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<parent title="Sessioner" link="Sessions.php"/>'.
'<titlebar title="'.$id.'" icon="Basic/Time">'.
'<close link="Sessions.php"/>'.
'</titlebar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="SessionDetailsList.php?id='.$id.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame");

	
writeGui($xwg_skin,$elements,$gui);

?>