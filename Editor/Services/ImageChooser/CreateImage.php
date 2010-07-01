<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.ImageChooser
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Include/Images.php';
require_once '../../Classes/Image.php';

// hide warnings
error_reporting(E_ERROR);

$title = requestPostText('title');
$group = getServiceSessionVar('imagechooser','group',0);

$response = createUploadedImage($title,$group);

if ($response['success']==true) {
	redirect('Icons.php?selectImage='.$response['id']);
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface>'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Advarsel"/>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Stop">'.
	'<title>Billedet kunne ikke oprettes</title>'.
	'<description>'.encodeXML($response['errorMessage']).'</description>'.
	($response['errorDetails']!=null ?
	'<error badge="Vis fejl">'.encodeXML($response['errorDetails']).'</error>'
	: '').
	'<buttongroup size="Large">'.
	'<button title="OK" link="Icons.php" style="Hilited"/>'.
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