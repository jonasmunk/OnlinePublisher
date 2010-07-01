<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Milestone.php';

$id = requestGetNumber('id');

$milestone = Milestone::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.encodeXML($milestone->getTitle()).'" icon="Basic/Time">'.
'<close link="Milestones.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Luk" icon="Basic/Close" link="Milestones.php"/>'.
'<tool title="Egenskaber" icon="Basic/Info" link="EditMilestone.php?id='.$id.'"/>'.
'<tool title="Ny opgave" icon="Part/Generic" overlay="New" link="NewTask.php?return='.urlencode('Milestone.php?id='.$id).'&amp;milestone='.$id.'"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="MilestoneList.php?id='.$id.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>