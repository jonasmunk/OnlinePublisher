<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'ProjectsController.php';

$id = Request::getInt('id');

ProjectsController::setProjectScope(Request::getString('scope'));
$scope = ProjectsController::getProjectScope();

ProjectsController::setProjectListState(Request::getString('state'));
$state = ProjectsController::getProjectListState();

$includeSubProjects = ($scope=='includesubprojects');

$filter = array();
$filter['includeSubProjects'] = ($scope=='includesubprojects');
if ($state!='any') {
	$filter['completed'] = ($state=='onlycompleted');
}

$project = Project::load($id);
$subProjects = $project->getSubProjects($filter);
$subTasks = $project->getSubTasks($filter);
$subProblems = $project->getSubProblems($filter);
$milestones = $project->getMilestones($filter);

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Omfang">'.
'<selection value="'.$scope.'" object="Scope">'.
'<item title="Kun dette projekt" value="onlythisproject"/>'.
'<item title="Også underprojekter" value="includesubprojects"/>'.
'</selection>'.
'</block>'.
'<block title="Status">'.
'<selection value="'.$state.'" object="State">'.
'<item title="Alle" value="any"/>'.
'<item title="Kun aktive" value="onlyactive"/>'.
'<item title="Kun fuldførte" value="onlycompleted"/>'.
'</selection>'.
'</block>'.
'</sidebar>'.
'<content>';
if (count($subTasks)>0) {
	$gui.=
	'<group title="Opgaver" open="'.(ProjectsController::getGroupingOpen('tasks') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=tasks&amp;open=false" open-action="ToggleGrouping.php?type=tasks&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Opgave" width="40%"/>'.
	'<header title="Milepæl" width="15%" type="text"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="15%" type="number"/>'.
	'<header title="Prioritet" width="15%" type="number"/>'.
	'</headergroup>';
	foreach ($subTasks as $subTask) {
		$milestone = Milestone::load($subTask->getMilestoneId());
	    $gui.=
	    '<row link="EditTask.php?id='.$subTask->getId().'&amp;returnProject='.$id.'" target="_parent">'.
	    '<cell><icon icon="Part/Generic"/><text>'.StringUtils::escapeXML($subTask->getTitle()).'</text></cell>'.
		'<cell>'.($milestone ? StringUtils::escapeXML($milestone->getTitle()) : '').'</cell>'.
	    '<cell index="'.$subTask->getCompleted().'">'.($subTask->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
		'<cell index="'.$subTask->getDeadline().'">'.
		($subTask->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$subTask->getDeadline()).'</text>'.
		(!$subTask->getCompleted() && $subTask->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '<cell index="'.round($subTask->getPriority()*100).'"><progress value="'.round($subTask->getPriority()*100).'"/></cell>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}
if (count($subProblems)>0) {
	$gui.=
	'<group title="Problemer" open="'.(ProjectsController::getGroupingOpen('problems') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=problems&amp;open=false" open-action="ToggleGrouping.php?type=problems&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Problem" width="40%"/>'.
	'<header title="Milepæl" width="15%" type="text"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="15%" type="number"/>'.
	'<header title="Prioritet" width="15%" type="number"/>'.
	'</headergroup>';
	foreach ($subProblems as $subProblem) {
		$milestone = Milestone::load($subProblem->getMilestoneId());
	    $gui.=
	    '<row link="EditProblem.php?id='.$subProblem->getId().'&amp;returnProject='.$id.'" target="_parent">'.
	    '<cell><icon icon="Basic/Stop"/><text>'.StringUtils::escapeXML($subProblem->getTitle()).'</text></cell>'.
		'<cell>'.($milestone ? StringUtils::escapeXML($milestone->getTitle()) : '').'</cell>'.
	    '<cell index="'.$subProblem->getCompleted().'">'.($subProblem->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$subProblem->getDeadline().'">'.
		($subProblem->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$subProblem->getDeadline()).'</text>'.
		(!$subProblem->getCompleted() && $subProblem->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '<cell index="'.round($subProblem->getPriority()*100).'"><progress value="'.round($subProblem->getPriority()*100).'"/></cell>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}
if (count($milestones)>0) {
	$gui.=
	'<group title="Milepæle" open="'.(ProjectsController::getGroupingOpen('milestones') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=milestones&amp;open=false" open-action="ToggleGrouping.php?type=milestones&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Milepæl" width="55%"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="15%" type="number"/>'.
	'<header title="" width="15%"/>'.
	'</headergroup>';
	foreach ($milestones as $milestone) {
	    $gui.=
	    '<row>'.
	    '<cell><icon icon="'.$milestone->getIcon().'"/><text>'.StringUtils::escapeXML($milestone->getTitle()).'</text></cell>'.
	    '<cell index="'.$milestone->getCompleted().'">'.($milestone->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$milestone->getDeadline().'">'.
		($milestone->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$milestone->getDeadline()).'</text>'.
		(!$milestone->getCompleted() && $milestone->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
		'<cell/>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}
if (count($subProjects)>0) {
	$gui.=
	'<group title="Underprojekter" open="'.(ProjectsController::getGroupingOpen('projects') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=projects&amp;open=false" open-action="ToggleGrouping.php?type=projects&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Projekt" width="85%"/>'.
	'<header title="Opdateret" width="15%"/>'.
	'</headergroup>';
	foreach ($subProjects as $subProject) {
	    $gui.=
	    '<row link="Project.php?id='.$subProject->getId().'" target="_parent">'.
	    '<cell><icon icon="Tool/Knowledgebase"/><text>'.StringUtils::escapeXML($subProject->getTitle()).'</text></cell>'.
	    '<cell>'.date('d/m-Y',$subProject->getUpdated()).'</cell>'.
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
var sideBarDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "ProjectList.php?id='.$id.'&amp;scope="+Scope.getValue()+"&amp;state="+State.getValue();
	}
}
Scope.setDelegate(sideBarDelegate);
State.setDelegate(sideBarDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Result","List","Script");
writeGui($xwg_skin,$elements,$gui);
?>