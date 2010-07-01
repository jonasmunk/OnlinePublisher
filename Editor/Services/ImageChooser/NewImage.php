<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.ImageChooser
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<window xmlns="uri:Window" width="400" align="center" top="20">'.
'<titlebar title="Nyt billede" icon="Element/Image">'.
'<close link="Icons.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateImage.php" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<file badge="Fil:" name="file"/>'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Icons.php"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
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