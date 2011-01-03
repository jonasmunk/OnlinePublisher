
<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$close = 'Editor.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="20">';
if (requestGetText('error')=='invalid') {
	$gui.='<sheet width="300" object="ErrorSheet" visible="true">'.
	'<message xmlns="uri:Message" icon="Caution">'.
	'<title>Ikke et HTML-dokument</title>'.
	'<description>Den uploadede fil var ikke et validt HTML-dokument</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="javascript: ErrorSheet.hide();"/>'.
	'</buttongroup>'.
	'</message>'.
	'</sheet>';
}
$gui.=
'<titlebar title="Upload af HTML-dokument" icon="File/html">'.
'<close link="'.$close.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<text xmlns="uri:Text" align="center" top="5" bottom="10">'.
'<strong>Vælg et HTML-dokument</strong><break/>'.
'<small>Du kan her uploade et HTML dokument fra din lokale computer</small>'.
'</text>'.
'<form xmlns="uri:Form" action="ProcessUpload.php" method="post" name="Formula" enctype="multipart/form-data">'.
'<group size="Large">'.
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

$elements = array("Window","Toolbar","Form","Message","Text");
writeGui($xwg_skin,$elements,$gui);
?>