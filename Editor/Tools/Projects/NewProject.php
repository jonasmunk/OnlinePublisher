<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Project.php';
require_once '../../Classes/Request.php';

$parent = Request::getInt('parent');
if ($parent>0) {
	$close = 'Project.php?id='.$parent;
} else {
	$close = 'Overview.php';
}

$projectOptions = Project::optionSpider('',0,0);


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Nyt projekt" icon="Tool/Knowledgebase">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateProject.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<select name="parentProject" badge="Overprojekt" selected="'.$parent.'">'.
'<option title="Intet" value="0"/>'.
$projectOptions.
'</select>'.
'<textfield badge="Beskrivelse:" name="description" lines="6"/>'.
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