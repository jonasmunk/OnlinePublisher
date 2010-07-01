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
require_once '../../Classes/File.php';

require_once 'Functions.php';

$title = requestPostText('title');
$group=getToolSessionVar('files','group');

$response = File::createUploadedFile($title,$group);

if ($response['success']==true) {
	setToolSessionVar('files','updateHierarchy',true);
	if ($group>0)
		redirect('Group.php');
	else
		redirect('Library.php');
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Advarsel"/>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Stop">'.
	'<title>Filen kunne ikke oprettes</title>'.
	'<description>'.encodeXML($response['errorMessage']).'</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="NewFile.php" style="Hilited"/>'.
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