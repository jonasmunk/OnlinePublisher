<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" size="2" spacing="10" width="100%" cellwidth="20%" padding="0" titles="right">';
$tools = InternalSession::getTools();
foreach ($tools as $tool) {
	$actions = $tool['actions'];
	foreach ($actions as $action) {
		$gui.='<row>'.
		'<icon icon="'.$action['icon'].'" title="'.$action['name'].'" overlay="'.$action['overlay'].'" link="../../Tools/'.$tool['unique'].'/?action='.$action['unique'].'" target="Desktop" description="'.$action['description'].'"/>'.
		'</row>';
	}
}
$gui.=
'</group>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Icon");
writeGui($xwg_skin,$elements,$gui);
?>