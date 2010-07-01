<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/Templates.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area xmlns="uri:Area" width="100%">'.
'<content padding="5">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Skabelon" width="30%"/>'.
'<header title="Beskrivelse" width="60%"/>'.
'<header width="1%"/>'.
'</headergroup>';
$installed = getInstalledTemplates();
$available = getAvailableTemplates();
foreach ($available as $template) {
	$inst = isInstalled($template,$installed);
	$info = getTemplateInfo($template);
	$gui.='<row>'.
	'<cell><icon icon="'.$info['icon'].'"/><text>'.$info['name'].'</text></cell>'.
	'<cell>'.$info['description'].'</cell>'.
	'<cell>'.
	($inst ?
	'<button title="Install" style="Disabled"/>'
	:
	'<button title="Install" link="TemplatesInstall.php?unique='.$template.'"/>'
	).
	'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","List");
writeGui($xwg_skin,$elements,$gui);

function isInstalled($template,$installed) {
	$out = false;
	foreach ($installed as $inst) {
		if ($inst['unique']==$template) {
			$out = true;
		}
	}
	return $out;
}
?>