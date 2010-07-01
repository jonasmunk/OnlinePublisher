<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Settings.php';

$tab = requestGetText('tab');
if (!$tab) {
	$tab = Settings::getServiceSetting('start','status.tab');
	if ($tab==null) $tab='problems';
} else {
	Settings::setServiceSetting('start','status.tab',$tab);
}
if ($tab=='problems') {
	$source = 'Problems.php';
} else if ($tab=='statistics') {
	$source = 'Statistics.php';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Status"/>'.
'<tabgroup>'.
'<tab title="Problemer"'.($tab=='problems' ? ' style="Hilited"' : ' link="Status.php?tab=problems"').'/>'.
'<tab title="Statistik"'.($tab=='statistics' ? ' style="Hilited"' : ' link="Status.php?tab=statistics"').'/>'.
'</tabgroup>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="'.$source.'"/>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame","Area");
writeGui($xwg_skin,$elements,$gui);
?>