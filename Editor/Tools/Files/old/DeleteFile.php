<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once '../../Classes/File.php';
require_once 'Functions.php';

$id = requestGetNumber('id',0);
$file = File::load($id);

$filename=$file->getFilename();
if (!unlink ($basePath.'files/'.$filename)) {
	$errorMessage='Kunne ikke slette filen fra serveren';
}

if (isset($errorMessage)) {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Fejl"/>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Error">'.
	'<title>Filen kunne ikke slettes</title>'.
	'<description>'.encodeXML($errorMessage).'</description>'.
	'<buttongroup size="Large">'.
	'<button title="OK" link="File.php?id='.$id.'" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Message");
	writeGui($xwg_skin,$elements,$gui);
}
else {
	$file->remove();

	setToolSessionVar('files','updateHierarchy',true);
	$group=getToolSessionVar('files','group');
	if ($group>0) {
		redirect('Group.php');
	}
	else {
		redirect('Library.php');
	}
}
?>