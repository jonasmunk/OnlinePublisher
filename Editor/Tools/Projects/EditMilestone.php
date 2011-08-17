<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Objects/Milestone.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id');
$return = 'Milestones.php';	

$milestone = Milestone::load($id);
$projectOptions = Project::optionSpider('',0,$id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Egenskaber for milepæl" icon="Basic/Info">'.
'<close link="'.$return.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateMilestone.php" method="post" name="Formula" focus="title">'.
'<hidden name="return">'.$return.'</hidden>'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($milestone->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="6">'.
StringUtils::escapeXML($milestone->getNote()).
'</textfield>';
if ($milestone->getDeadline() > 0) {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime($milestone->getDeadline()).'">'.
    '<check name="deadlineSelected" selected="true"/>'.
    '</datetime>';
} else {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime(mktime()).'">'.
    '<check name="deadlineSelected"/>'.
    '</datetime>';
}
$gui.=
'<checkbox badge="Fuldført:" name="completed" selected="'.($milestone->getCompleted() ? 'true' : 'false').'"/>'.
'<space/>'.
'<select name="project" badge="Projekt" selected="'.$milestone->getContainingObjectId().'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteMilestone.php?id='.$id.'"/>'.
'<button title="Annuller" link="'.$return.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
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