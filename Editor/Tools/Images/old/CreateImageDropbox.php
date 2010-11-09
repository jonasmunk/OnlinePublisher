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
require_once '../../Include/Images.php';
require_once '../../Classes/Image.php';
require_once 'ImagesController.php';

@set_time_limit(300);

$group = ImagesController::getGroupId();
$close = ImagesController::getBaseWindow();

$dir = $basePath.'dropbox/';
$files = requestPostArray('file');
$report = false;

if (count($files)>0) {
	$report = array('imported' => array(), 'problems' => array());
	foreach ($files as $file) {
		$result = createImageFromFile($file,basename($file),null,filesize($file),null,$group);
		if ($result['success']) {
			$report['imported'][] = substr($file,strlen($dir));
		} else {
			$report['problems'][] = substr($file,strlen($dir)).' kunne ikke importeres: '.$result['errorMessage'].
				(strlen($result['errorDetails'])>0 ? '. '.$result['errorDetails'] : '');
		}
	}
	ImagesController::setUpdateHierarchy(true);
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">';
if (!$report) {
	$gui.='<window xmlns="uri:Window" width="400" align="center" top="30">'.
	'<titlebar title="Advarsel">'.
	'<close link="'.$close.'"/>'.
	'</titlebar>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Caution">'.
	'<title>Ingen filer var valgt</title>'.
	'<description>Det er nødvendigt at du udpeger hvilke filer du vil importere.</description>'.
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="'.$close.'"/>'.
	'<button title="Prøv igen" link="NewImageDropbox.php" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>';
} else {
	$gui.='<window xmlns="uri:Window" width="450" align="center" top="30">'.
	'<titlebar title="Resultat af import" icon="Tool/Message">'.
	'<close link="'.$close.'"/>'.
	'</titlebar>'.
	'<content padding="8" background="true">'.
	'<text top="10" bottom="10" align="center" xmlns="uri:Text">'.
	'<strong>Filerne er nu importeret</strong><break/>'.
	'<small>Her kan du se hvilke billeder der blevet importeret fra drop-boksen.'.
	'<break/>Desuden vises hvilke filer der ikke kunne importeres.</small>'.
	'</text>'.
	'<list width="100%" xmlns="uri:List">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Status" width="30%"/>'.
	'<header title="Filnavn" width="70%"/>'.
	'</headergroup>';
	foreach ($report['imported'] as $imported) {
		$gui.='<row>'.
		'<cell><status type="Finished"/><text>Importeret</text></cell>'.
		'<cell>'.encodeXML($imported).'</cell>'.
		'</row>';
	}
	foreach ($report['problems'] as $problem) {
		$gui.='<row>'.
		'<cell><status type="Error"/><text>Fejl</text></cell>'.
		'<cell>'.encodeXML($problem).'</cell>'.
		'</row>';
	}
	$gui.='</content></list>'.
	'<group size="Large" xmlns="uri:Button" align="right" top="8">'.
	'<button title="Tilbage" link="NewImageDropbox.php"/>'.
	'<button title="OK" link="'.$close.'" style="Hilited"/>'.
	'</group>'.
	'</content>';
}
$gui.=
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Message","List","Button","Text");
writeGui($xwg_skin,$elements,$gui);
?>