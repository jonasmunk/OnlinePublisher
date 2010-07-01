<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';

$mode = getRequestToolSessionVar('statistics','visitors.mode','mode','weeks');
$view = getRequestToolSessionVar('statistics','visitors.view','view','list');

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<tabgroup size="Large">'.
'<tab title="Liste"'.($view=='list' ? ' style="Hilited"' : ' link="Visitors.php?view=list"').'/>'.
'<tab title="Graf"'.($view=='graph' ? ' style="Hilited"' : ' link="Visitors.php?view=graph"').'/>'.
'</tabgroup>'.
'<titlebar title="Besøgende" icon="Tool/Statistics"/>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.($view=='list' ? 'VisitorsList.php' : 'Graph.php?data=VisitorsGraph.php').'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame");

	
writeGui($xwg_skin,$elements,$gui);

?>