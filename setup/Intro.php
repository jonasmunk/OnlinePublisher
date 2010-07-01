<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once 'Functions.php';
require_once 'Security.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<content padding="10">'.
'<text xmlns="uri:Text">'.
'<strong>Velkommen til opsætningen af OnlinePublisher</strong>'.
'<break/><break/>'.
'Med dette værtøj kan du ændre systemets konfiguration og sikre dig at det er opsat korrekt'.
'</text>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';
$elements = array("Area","Text");
writeGui($xwg_skin,$elements,$gui);
?>
