<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Task.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Milestone.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id');
if (Request::exists('return')) {
	$return = Request::getString('return');
} else {
	$return = 'Project.php?id='.Request::getInt('returnProject');	
}
$task = Task::load($id);

$projectOptions = Project::optionSpider('',0,$id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Egenskaber for opgave" icon="Basic/Info">'.
'<close link="'.$return.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateTask.php" method="post" name="Formula" focus="title">'.
'<hidden name="return">'.$return.'</hidden>'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($task->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="6">'.
StringUtils::escapeXML($task->getNote()).
'</textfield>';
if ($task->getDeadline() > 0) {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime($task->getDeadline()).'">'.
    '<check name="deadlineSelected" selected="true"/>'.
    '</datetime>';
} else {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime(mktime()).'">'.
    '<check name="deadlineSelected"/>'.
    '</datetime>';
}
$gui.=
'<number badge="Prioritet" name="priority" delimiter="," min="0" max="1" decimals="2" value="'.$task->getPriority().'"/>'.
'<checkbox badge="Fuldført:" name="completed" selected="'.($task->getCompleted() ? 'true' : 'false').'"/>'.
'<space/>'.
'<select name="parentProject" badge="Projekt" selected="'.$task->getContainingObjectId().'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.

'<object badge="Milepæl:" name="milestone" empty="true">';
	if ($task->getMilestoneId()>0) {
		$gui.=GuiUtils::buildEntity(Milestone::load($task->getMilestoneId()));
	}
	$gui.=
	'<translation choose="Vælg" remove="Fjern" none="Ingen valgt"/>'.
	'<source list="MilestonePicker.php"/>'.
'</object>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteTask.php?id='.$id.'&amp;return='.urlencode($return).'"/>'.
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