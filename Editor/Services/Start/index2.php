<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';

$tab = InternalSession::getRequestServiceSessionVar('start','tab','tab','tools');
switch ($tab) {
	case 'tools':
		$frame='Tools.php?';
		break;
	case 'actions':
		$frame='Actions.php?';
		break;
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<layout xmlns="uri:Layout" width="100%" height="100%" spacing="10">'.
'<row>'.
'<cell width="276" height="69">'.
'<html xmlns="uri:Html">'.
'<img src="../../Resources/StartLogo.gif" width="276" height="69" border="0"/>'.
'</html>'.
'</cell>'.
'<cell rowspan="2">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Handlinger"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Actions.php"/>'.
'</content>'.
'</area>'.
'</cell>'.
'<cell width="45%" rowspan="2">'.
'<iframe xmlns="uri:Frame" source="Status.php"/>'.
'</cell>'.
'</row>'.
'<row>'.
'<cell width="276" rowspan="2">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Værktøjer"/>'.
'<content>'.
'<iframe xmlns="uri:Frame" source="Tools.php"/>'.
'</content>'.
'</area>'.
'</cell>'.
'</row>'.
'<row>'.
'<cell colspan="2" height="40%">'.
'<iframe xmlns="uri:Frame" source="Support.php"/>'.
'</cell>'.
'</row>'.
'</layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Layout","Area","Frame","Button","Html");
writeGui($xwg_skin,$elements,$gui);

?>