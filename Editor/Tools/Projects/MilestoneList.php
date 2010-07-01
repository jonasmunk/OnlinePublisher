<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Milestone.php';
require_once '../../Classes/In2iGui.php';
require_once 'ProjectsController.php';

$id = requestGetNumber('id');
$milestone = Milestone::load($id);
$tasks = $milestone->getTasks();
$problems = $milestone->getProblems();

ProjectsController::setMilstoneGrouping(requestGetText('grouping'));
$grouping = ProjectsController::getMilstoneGrouping();

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Gruppér efter">'.
'<selection value="'.$grouping.'" object="Grouping">'.
'<item title="Type" value="type"/>'.
'<item title="Status" value="status"/>'.
'</selection>'.
'</block>'.
'</sidebar>'.
'<content>';
if (count($tasks)>0) {
	$gui.=
	'<group title="Opgaver">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Opgave" width="55%"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="15%" type="number"/>'.
	'<header title="Prioritet" width="15%" type="number"/>'.
	'</headergroup>';
	foreach ($tasks as $task) {
	    $gui.=
	    '<row link="EditTask.php?id='.$task->getId().'&amp;return='.urlencode('Milestone.php?id='.$id).'" target="_parent">'.
	    '<cell><icon icon="'.$task->getIcon().'"/><text>'.In2iGui::escape($task->getTitle()).'</text></cell>'.
	    '<cell index="'.$task->getCompleted().'">'.($task->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$task->getDeadline().'">'.
		($task->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$task->getDeadline()).'</text>'.
		(!$task->getCompleted() && $task->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '<cell index="'.round($task->getPriority()*100).'"><progress value="'.round($task->getPriority()*100).'"/></cell>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}
if (count($problems)>0) {
	$gui.=
	'<group title="Problem">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Problem" width="55%"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="15%" type="number"/>'.
	'<header title="Prioritet" width="15%" type="number"/>'.
	'</headergroup>';
	foreach ($problems as $problem) {
	    $gui.=
	    '<row link="EditProblem.php?id='.$problem->getId().'&amp;return='.urlencode('Milestone.php?id='.$id).'" target="_parent">'.
	    '<cell><icon icon="'.$problem->getIcon().'"/><text>'.In2iGui::escape($problem->getTitle()).'</text></cell>'.
	    '<cell index="'.$problem->getCompleted().'">'.($problem->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$problem->getDeadline().'">'.
		($problem->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$problem->getDeadline()).'</text>'.
		(!$problem->getCompleted() && $problem->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '<cell index="'.round($problem->getPriority()*100).'"><progress value="'.round($problem->getPriority()*100).'"/></cell>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}
$gui.=
'</content>'.
'</result>'.
'<script xmlns="uri:Script">
var groupingDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "MilestoneList.php?id='.$id.'&amp;grouping="+Grouping.getValue();
	}
}
Grouping.setDelegate(groupingDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Result","List","Script");
In2iGui::display($elements,$gui);
?>