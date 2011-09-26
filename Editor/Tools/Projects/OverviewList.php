<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Project.php';
require_once '../../Classes/Objects/Task.php';
require_once '../../Classes/Objects/Problem.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'ProjectsController.php';

ProjectsController::setGroupView(Request::getString('groupView'));
$groupView = ProjectsController::getGroupView();
ProjectsController::setTimeView(Request::getString('timeView'));
$timeView = ProjectsController::getTimeView();

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<sidebar>'.
'<block title="Grupper efter">'.
'<selection value="'.$groupView.'" object="GroupView">'.
'<item title="Type" value="type"/>'.
'<item title="Status" value="state"/>'.
'</selection>'.
'</block>'.
'</sidebar>'.
'<content>';
if ($groupView=='type') {
    buildContentType($gui);
} elseif ($groupView=='state') {
    buildContentState($gui);
}
$gui.=
'</content>'.
'</result>'.
'<script xmlns="uri:Script">
var viewDelegate = {
	valueDidChange : function(event,obj) {
		document.location = "OverviewList.php?groupView="+
		                    GroupView.getValue();
	}
}
GroupView.setDelegate(viewDelegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Result","List","Script");
writeGui($xwg_skin,$elements,$gui);

function buildContentType(&$gui) {
	$gui.=
	'<group title="Opgaver" open="'.(ProjectsController::getGroupingOpen('tasks') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=tasks&amp;open=false" open-action="ToggleGrouping.php?type=tasks&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Opgave" width="45%"/>'.
	'<header title="Projekt" width="20%"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="20%" type="number"/>'.
	'</headergroup>';

	$sql = "select object.id,object.title,task.completed,task.deadline,project.object_id as project_id from object join task on object.id=task.object_id left join project on project.object_id=task.containing_object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$task = Task::load($row['id']);
		if ($row['project_id']!='') {
			$project = Project::load($row['project_id']);
		} else {
			$project = false;
		}
	    $gui.=
	    '<row link="EditTask.php?id='.$task->getId().'&amp;return=Overview.php" target="_parent">'.
	    '<cell><icon icon="Part/Generic"/><text>'.StringUtils::escapeXML($task->getTitle()).'</text></cell>'.
		($project ?
			'<cell link="Project.php?id='.$project->getId().'"><icon icon="Tool/Knowledgebase"/><text>'.StringUtils::escapeXML($project->getTitle()).'</text></cell>'
		: '<cell/>').
	    '<cell index="'.$task->getCompleted().'">'.($task->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$task->getDeadline().'">'.
		($task->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$task->getDeadline()).'</text>'.
		(!$task->getCompleted() && $task->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '</row>';
	}

	$gui.=
	'</content>'.
	'</list>'.
	'</group>'.
	'<group title="Problemer" open="'.(ProjectsController::getGroupingOpen('problems') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=problems&amp;open=false" open-action="ToggleGrouping.php?type=problems&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Problem" width="45%"/>'.
	'<header title="Projekt" width="20%"/>'.
	'<header title="Status" width="15%" type="number"/>'.
	'<header title="Deadline" width="20%" type="number"/>'.
	'</headergroup>';

	$sql = "select object.id,object.title,problem.completed,problem.deadline,project.object_id as project_id from object,problem left join project on project.object_id=problem.containing_object_id where object.id=problem.object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$problem = Problem::load($row['id']);
		if ($row['project_id']!='') {
			$project = Project::load($row['project_id']);
		} else {
			$project = false;
		}
	    $gui.=
	    '<row link="EditProblem.php?id='.$problem->getId().'&amp;return=Overview.php" target="_parent">'.
	    '<cell><icon icon="Basic/Stop"/><text>'.StringUtils::escapeXML($problem->getTitle()).'</text></cell>'.
		($project ?
			'<cell link="Project.php?id='.$project->getId().'"><icon icon="Tool/Knowledgebase"/><text>'.StringUtils::escapeXML($project->getTitle()).'</text></cell>'
		: '<cell/>').
	    '<cell index="'.$problem->getCompleted().'">'.($problem->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$problem->getDeadline().'">'.
		($problem->getDeadline()>0 ?
		'<text>'.date('d/m-Y',$problem->getDeadline()).'</text>'.
		(!$problem->getCompleted() && $problem->getDeadline()<time() ? '<status type="Attention"/>' : '')
		: '').
		'</cell>'.
	    '</row>';
	}

	$gui.=
	'</content>'.
	'</list>'.
	'</group>'.
	'<group title="Projekter" open="'.(ProjectsController::getGroupingOpen('projects') ? 'true' : 'false').'" close-action="ToggleGrouping.php?type=projects&amp;open=false" open-action="ToggleGrouping.php?type=projects&amp;open=true">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Projekt" width="65%"/>'.
	'<header title="Status" type="number" width="15%"/>'.
	'<header title="Opdateret" type="number" width="20%"/>'.
	'</headergroup>';

	$sql = "select object.id,object.title,count(task.completed) as completed from object join project on object.id = project.object_id left join task on task.containing_object_id=project.object_id and (task.completed=0 or task.completed=NULL) group by project.object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$project = Project::load($row['id']);
	    $gui.=
	    '<row link="Project.php?id='.$project->getId().'" target="_parent">'.
	    '<cell><icon icon="Tool/Knowledgebase"/><text>'.StringUtils::escapeXML($project->getTitle()).'</text></cell>'.
	    '<cell index="'.$row['completed'].'">'.($row['completed']<1 ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
	    '<cell index="'.$project->getUpdated().'">'.date('d/m-Y',$project->getUpdated()).'</cell>'.
	    '</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}

function buildContentState(&$gui) {
    $completed = array();
    $notCompleted = array();

	$sql = "select object.id,count(task.completed) as active, max(task.deadline) as deadline from project,object left join task on task.containing_object_id=project.object_id and (task.completed=0 or task.completed=NULL) where object.id = project.object_id group by project.object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$project = Project::load($row['id']);
		if ($row['active']>0) {
		    $notCompleted[] = $project;
		} else {
		    $completed[] = $project;
		}
	}


	$sql = "select object.id,task.completed,project.object_id as project_id from object,task left join project on project.object_id=task.containing_object_id where object.id=task.object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$task = Task::load($row['id']);
		if ($task->getCompleted()) {
		    $completed[] = $task;
		} else {
		    $notCompleted[] = $task;
		}
	}


	$sql = "select object.id,problem.completed,project.object_id as project_id from object,problem left join project on project.object_id=problem.containing_object_id where object.id=problem.object_id order by object.title";
	$result = Database::select($sql);
	$rows = array();
	while ($row = Database::next($result)) {
		$rows[] = $row;
	}
	Database::free($result);

	for ($i=0;$i<count($rows);$i++) {
		$row = $rows[$i];
		$problem = Problem::load($row['id']);
		if ($problem->getCompleted()) {
		    $completed[] = $problem;
		} else {
		    $notCompleted[] = $problem;
		}
	}


	$gui.=
	'<group title="Aktive">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Titel" width="65%"/>'.
	'<header title="Type" width="15%"/>'.
	'<header title="Deadline" width="20%"/>'.
	'</headergroup>';
	foreach ($notCompleted as $object) {
	    buildRow($object,$gui,false);
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>'.
	'<group title="Fuldførte">'.
	'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Titel" width="65%"/>'.
	'<header title="Type" width="15%"/>'.
	'<header title="Deadline" width="20%"/>'.
	'</headergroup>';
	foreach ($completed as $object) {
	    buildRow($object,$gui,true);
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</group>';
}

function buildRow(&$object,&$gui,$completed) {
    if ($object->getType()=='task') {
        $link = 'EditTask.php?id='.$object->getId().'&amp;return=Overview.php';
        $deadline = $object->getDeadline();
        $type = 'Opgave';
    } elseif ($object->getType()=='problem') {
        $link = 'EditProblem.php?id='.$object->getId().'&amp;return=Overview.php';
        $deadline = $object->getDeadline();
        $type = 'Problem';
    } elseif ($object->getType()=='project') {
        $link = 'Project.php?id='.$object->getId();
        $deadline = $object->getMaxFutureDeadlineOfChildren();
        $type = 'Projekt';
    }
    $gui.=
    '<row link="'.$link.'" target="_parent">'.
    '<cell><icon icon="'.$object->getIcon().'"/>'.
    '<text>'.StringUtils::escapeXML($object->getTitle()).'</text></cell>'.
    '<cell>'.$type.'</cell>'.
    '<cell index="'.$deadline.'">'.($deadline>0 ? date('d/m-Y',$deadline) : '').'</cell>'.
    '</row>';
}
?>