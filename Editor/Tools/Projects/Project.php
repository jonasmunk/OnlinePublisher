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
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id');

$project = Project::load($id);
$path = $project->getPath(true);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="'.StringUtils::escapeXML($project->getTitle()).'" icon="Tool/Knowledgebase">'.
'<close link="Overview.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
($project->getParentProjectId()>0
? '<direction title="Tilbage" direction="Left" link="Project.php?id='.$project->getParentProjectId().'"/>'
: '<tool title="Luk" icon="Basic/Close" link="Overview.php"/>'
).
'<tool title="Egenskaber" icon="Basic/Info" link="EditProject.php?id='.$id.'"/>'.
'<divider/>'.
'<tool title="Nyt projekt" icon="Tool/Knowledgebase" overlay="New" link="NewProject.php?parent='.$id.'"/>'.
'<tool title="Ny opgave" icon="Part/Generic" overlay="New" link="NewTask.php?project='.$id.'"/>'.
'<tool title="Nyt problem" icon="Basic/Stop" overlay="New" link="NewProblem.php?project='.$id.'"/>'.
'</toolbar>'.
'<pathbar>';
foreach ($path as $item) {
    $gui.='<item title="'.StringUtils::escapeXML($item['title']).'" link="Project.php?id='.$item['id'].'"/>';
}
$gui.=
'</pathbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ProjectList.php?id='.$id.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>