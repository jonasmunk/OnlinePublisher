<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once '../Editor/Classes/Settings.php';
require_once 'Functions.php';
require_once 'Security.php';


$workerServer = Settings::getSetting('system','environment','worker-server-address');
$hasNeato = Settings::getSetting('system','environment','neato');
$extraPath = Settings::getSetting('system','environment','extrapath');


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<text align="center" top="5" bottom="5" xmlns="uri:Text">'.
'<strong>Integration med andre systemer</strong>'.
'<break/>'.
'Her kan du opsætte systemet til at interagere med andre systemer...'.
'</text>'.
'<form action="IntegrationUpdate.php" method="post" xmlns="uri:Form" submit="true">'.
'<group size="Large">'.
'<checkbox badge="NEATO:" name="neato" selected="'.($hasNeato ? 'true' : 'false').'"/>'.
'<textfield badge="Ekstra shell-sti:" name="extrapath">'.encodeXML($extraPath).'</textfield>'.
'<textfield badge="Worker-server adresse:" name="worker-server-address">'.encodeXML($workerServer).'</textfield>'.
'<buttongroup size="Large">'.
'<button title="Gem" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Form","Layout","Text");
writeGui($xwg_skin,$elements,$gui);
?>