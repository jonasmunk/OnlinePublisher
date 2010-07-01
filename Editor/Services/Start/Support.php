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
	$tab = Settings::getServiceSetting('start','support.tab');
	if ($tab==null) $tab='links';
} else {
	Settings::setServiceSetting('start','support.tab',$tab);
}
if ($tab=='links') {
	$source = 'Links.php';
} else if ($tab=='news') {
	$source = 'News.php';
} else if ($tab=='feedback') {
	$source = 'Feedback.php';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Support"/>'.
'<tabgroup>'.
'<tab title="Links"'.($tab=='links' ? ' style="Hilited"' : ' link="Support.php?tab=links"').'/>'.
'<tab title="Nyheder"'.($tab=='news' ? ' style="Hilited"' : ' link="Support.php?tab=news"').'/>'.
'<tab title="Feedback"'.($tab=='feedback' ? ' style="Hilited"' : ' link="Support.php?tab=feedback"').'/>'.
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