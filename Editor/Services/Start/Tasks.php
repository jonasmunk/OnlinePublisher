<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<text align="center" top="10" xmlns="uri:Text">Der er i øjeblikket ingen opgaver</text>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Text");
writeGui($xwg_skin,$elements,$gui);
?>