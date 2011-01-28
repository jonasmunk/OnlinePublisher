<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Milestone.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Request.php';


$projectOptions = Project::optionSpider('',0,0);

$project = Request::getInt('project');
$milestone = Request::getInt('milestone');
if (Request::exists('return')) {
	$close = Request::getString('return');
} else {
	$close = 'Project.php?id='.$project;
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Ny opgave" icon="Part/Generic">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateTask.php" method="post" name="Formula" focus="title" submit="true">'.
'<hidden name="return">'.$close.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Beskrivelse:" name="description" lines="6"/>'.
'<datetime badge="Deadline:" name="deadline" display="dmy" value="'.xwgTimeStamp2dateTime(mktime()).'">'.
'<check name="deadlineSelected"/>'.
'</datetime>'.
'<number badge="Prioritet" name="priority" delimiter="," min="0" max="1" decimals="2"/>'.
'<space/>'.
'<select name="parentProject" badge="Projekt" selected="'.$project.'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<object badge="Milepæl:" name="milestone" empty="true">';
	if ($milestone>0) {
		$gui.=GuiUtils::buildEntity(Milestone::load($milestone));
	}
	$gui.=
	'<translation choose="Vælg" remove="Fjern" none="Ingen valgt"/>'.
	'<source list="MilestonePicker.php"/>'.
'</object>'.
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