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
require_once 'CalendarsController.php';

CalendarsController::setSelection('overview');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Oversigt over kalendere" icon="Tool/Calendar"/>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Ny kalender" icon="Tool/Calendar" overlay="New" link="NewCalendar.php"/>'.
'<tool title="Ny kilde" icon="Basic/Internet" overlay="New" link="NewSource.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="OverviewList.php" object="List"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>