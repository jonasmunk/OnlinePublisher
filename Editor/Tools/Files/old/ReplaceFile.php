<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

$id = requestGetNumber('id');
$close = 'File.php?id='.$id;

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Erstat fil" icon="File/Generic">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="FileReplacer.php" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<file badge="Fil:" name="file"/>'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Erstat" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>