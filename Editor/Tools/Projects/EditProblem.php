<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Problem.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Milestone.php';

$id=requestGetNumber('id');
if (requestGetExists('return')) {
	$return = requestGetText('return');
} else {
	$return = 'Project.php?id='.requestGetNumber('returnProject');	
}
$problem = Problem::load($id);

$projectOptions = Project::optionSpider('',0,$id);

$milestones = Milestone::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Egenskaber for problem" icon="Basic/Info">'.
'<close link="'.$return.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateProblem.php" method="post" name="Formula" focus="title">'.
'<hidden name="return">'.$return.'</hidden>'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.encodeXML($problem->getTitle()).'</textfield>'.
'<textfield badge="Beskrivelse:" name="description" lines="6">'.
encodeXML($problem->getNote()).
'</textfield>';
if ($problem->getDeadline() > 0) {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime($problem->getDeadline()).'">'.
    '<check name="deadlineSelected" selected="true"/>'.
    '</datetime>';
} else {
    $gui.=
    '<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime(mktime()).'">'.
    '<check name="deadlineSelected"/>'.
    '</datetime>';
}
$gui.=
'<number badge="Prioritet" name="priority" delimiter="," min="0" max="1" decimals="2" value="'.$problem->getPriority().'"/>'.
'<checkbox badge="Fuldført:" name="completed" selected="'.($problem->getCompleted() ? 'true' : 'false').'"/>'.
'<space/>'.
'<select name="parentProject" badge="Projekt:" selected="'.$problem->getContainingObjectId().'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<select name="milestone" badge="Milepæl:" selected="'.$problem->getMilestoneId().'">'.
'<option title="Ingen" value="0"/>';
foreach ($milestones as $milestone) {
	$gui.='<option title="'.encodeXML($milestone->getTitle()).'" value="'.$milestone->getId().'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteProblem.php?id='.$id.'&amp;return='.urlencode($return).'"/>'.
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