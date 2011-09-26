<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Objects/Project.php';
require_once '../../Classes/Objects/Milestone.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$projectOptions = Project::optionSpider('',0,0);

$milestones = Milestone::search();
$project = Request::getInt('project');
$close = 'Project.php?id='.$project;

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Nyt problem" icon="Basic/Stop">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateProblem.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Beskrivelse:" name="description" lines="6"/>'.
'<datetime badge="Deadline:" name="deadline" display="dmy" value="'.
    xwgTimeStamp2dateTime(mktime()).
'">'.
'<check name="deadlineSelected"/>'.
'</datetime>'.
'<number badge="Prioritet" name="priority" delimiter="," min="0" max="1" decimals="2"/>'.
'<space/>'.
'<select name="parentProject" badge="Projekt" selected="'.$project.'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<select name="milestone" badge="Milepæl:">'.
'<option title="Ingen" value="0"/>';
foreach ($milestones as $milestone) {
	$gui.='<option title="'.StringUtils::escapeXML($milestone->getTitle()).'" value="'.$milestone->getId().'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
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