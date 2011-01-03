<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalProblem.php';
require_once '../../Classes/Utilities/StringUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface>'.
'<list margin="2" width="100%" variant="Light" xmlns="uri:List">'.
'<content>'.
'<headergroup>'.
'<header title="Enhed" widht="50%"/>'.
'<header title="Problem" width="50%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$problems = InternalProblem::findProblems();
foreach ($problems as $problem) {
	$title = $problem->getTitle();
	$gui.=
	'<row>'.
	'<cell>'.buildEnity($problem->getEntity()).'</cell>'.
	'<cell><status type="Active"/><text>'.StringUtils::escapeXML($title).'</text></cell>'.
	'<cell>'.buildActions($problem->getActions()).'</cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","List");
writeGui($xwg_skin,$elements,$gui);

function buildEnity($entity) {
	if ($entity) {
		return '<icon icon="Template/Generic"/>'.
		'<text>'.StringUtils::escapeXML($entity['title']).'</text>';
	} else {
		return '';
	}
}

function buildActions($actions) {
	$out ='';
	foreach ($actions as $action) {
		$out.='<button title="'.StringUtils::escapeXML($action['title']).'" link="../../'.$action['link'].'" target="'.$action['target'].'"/>';
	}
	return $out;
}
?>