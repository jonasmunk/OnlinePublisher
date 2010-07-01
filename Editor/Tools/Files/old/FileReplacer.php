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
require_once '../../Classes/File.php';
require_once 'Functions.php';

// hide warnings
error_reporting(E_ERROR);

$id = requestPostNumber('id');
$close = 'File.php?id='.$id;

$response = File::replaceUploadedFile($id);

if ($response['success']==true) {
	redirect($close);
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Advarsel"/>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Stop">'.
	'<title>Filen kunne ikke erstattes</title>'.
	'<description>'.encodeXML($response['errorMessage']).'</description>'.
	($response['errorDetails']!=null ?
	'<error badge="Vis fejl">'.encodeXML($response['errorDetails']).'</error>'
	: '').
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="'.$close.'"/>'.
	'<button title="Prøv igen" link="ReplaceFile.php?id='.$id.'" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Message");
	writeGui($xwg_skin,$elements,$gui);
}
?>