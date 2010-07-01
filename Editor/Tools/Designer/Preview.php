<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Templates.php';
require_once '../../Classes/UserInterface.php';

$close = "index.php";
$id = requestGetNumber('id');

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%" align="center" margin="20">'.
'<titlebar title="Visning" icon="Basic/Time">'.
'<close link="'.$close.'" help="Luk vinduet"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar">'.
'<tool title="Luk" icon="Basic/Close" link="'.$close.'"/>'.
'</toolbar>'.
'<content>'.
'<iframe source="../../Services/Preview/viewer/?stickyDesignId='.$id.'" xmlns="uri:Frame"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>