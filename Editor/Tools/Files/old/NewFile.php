<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

if (getToolSessionVar('files','group')>0)
	$close="Group.php";
else
	$close="Library.php";

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Ny fil" icon="File/Generic">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateFile.php" method="post" name="Formula" focus="title" enctype="multipart/form-data">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<file badge="Fil:" name="file"/>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'"/>'.
'<button title="Upload" submit="true" style="Hilited"/>'.
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