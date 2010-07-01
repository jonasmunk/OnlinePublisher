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

$ignoreInternalUsers = getToolSessionVar('statistics','ignoreInternalUsers',false);
$ignoreRobots = getToolSessionVar('statistics','ignoreRobots',false);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="UpdateSettings.php" method="post">'.
'<group size="Small">'.
'<checkbox badge="Frasorter kendte brugere" name="ignoreInternalUsers" selected="'.($ignoreInternalUsers ? "true" : "false").'"/>'.
'<checkbox badge="Frasorter robotter" name="ignoreRobots" selected="'.($ignoreRobots ? "true" : "false").'"/>'.
'<buttongroup size="Small">'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Form");
writeGui($xwg_skin,$elements,$gui);
?>