<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Objects/Project.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id');
$close = 'Project.php?id='.$id;
$project = Project::load($id);

$projectOptions = Project::optionSpider('',0,$id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Egenskaber for projekt" icon="Basic/Info">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateProject.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($project->getTitle()).'</textfield>'.
'<select name="parentProject" badge="Overprojekt" selected="'.$project->getParentProjectId().'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<textfield badge="Beskrivelse:" name="description" lines="6">'.
StringUtils::escapeXML($project->getNote()).
'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteProject.php?id='.$id.'"/>'.
'<button title="Annuller" link="'.$close.'"/>'.
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