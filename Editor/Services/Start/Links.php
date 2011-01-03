<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';

$tab = Request::getString('tab');
if (!$tab) $tab='links';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<layout width="100%" height="100%" xmlns="uri:Layout">'.
'<row><cell valign="middle">'.
'<group xmlns="uri:Icon" size="3" spacing="10" width="100%" cellwidth="33%" padding="0">'.
'<row>'.
'<icon icon="Tool/Help" title="Support sider" link="../Support/" target="Desktop"/>'.
'<icon icon="Logo/In2iSoft" title="Besøg In2iSoft" link="http://www.in2isoft.dk/" target="_blank"/>'.
'</row>'.
'</group>'.
'</cell></row></layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon","Layout");
writeGui($xwg_skin,$elements,$gui);
?>