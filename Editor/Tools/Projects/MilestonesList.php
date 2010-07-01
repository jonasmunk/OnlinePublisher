<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Milestone.php';

$id = requestGetNumber('id');
$milestones = Milestone::search(array('sort' => 'deadline'));

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<result xmlns="uri:Result">'.
'<content>'.
'<group title="Milepæle">'.
'<list xmlns="uri:List" width="100%" variant="Light" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Milepæl" width="30%"/>'.
'<header title="Projekt" width="25%"/>'.
'<header title="Status" width="15%"/>'.
'<header title="" width="20%"/>'.
'<header title="Deadline" width="10%"/>'.
'</headergroup>';
foreach ($milestones as $milestone) {
	$completedInfo = $milestone->getCompletedInfo();
	$completed = $completedInfo['completed']." af ".($completedInfo['completed']+$completedInfo['active'])." fuldført";
	$project = Project::load($milestone->getContainingObjectId());
    $gui.=
    '<row link="Milestone.php?id='.$milestone->getId().'" target="_parent">'.
    '<cell><icon icon="'.$milestone->getIcon().'"/><text>'.In2iGui::escape($milestone->getTitle()).'</text></cell>'.
	'<cell>'.($project ? In2iGui::escape($project->getTitle()) : '').'</cell>'.
	'<cell index="'.$milestone->getCompleted().'">'.($milestone->getCompleted() ? '<status type="Finished"/><text>Fuldført</text>' : '<status type="Active"/><text>Aktiv</text>').'</cell>'.
    '<cell>'.$completed.'</cell>'.
	'<cell>'.($milestone->getDeadline() ? date('d/m-Y',$milestone->getDeadline()) : '').'</cell>'.
    '</row>';
}
$gui.=
'</content>'.
'</list>'.
'</group>'.
'</content>'.
'</result>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Result","List");
In2iGui::display($elements,$gui);
?>